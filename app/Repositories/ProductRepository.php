<?php

namespace App\Repositories;

use App\Enums\FolderName;
use App\Enums\MediaTag;
use App\Models\Product;
use App\RepositoryInterfaces\ProductRepositoryInterface;
use App\Services\FileService;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\DB;

class ProductRepository extends BaseRepository implements ProductRepositoryInterface
{
    /** @var \App\Services\FileService */
    protected $fileService;

    public function __construct()
    {
        parent::__construct();
        $this->fileService = resolve(FileService::class);
    }

    /**
     * @return string
     */
    public function getModel()
    {
        return \App\Models\Product::class;
    }

    /**
     * @return array
     */
    public function filter()
    {
        return [
            ['code', 'like', '%', '%'],
            ['name', 'like', '%', '%'],
            'quantity_from' => ['quantity', '>='],
            'quantity_to' => ['quantity', '<='],
            'price_from' => ['price', '>='],
            'price_to' => ['price', '<='],
            'currency',
            ['origin', 'like', '%', '%'],
            'status'
        ];
    }

    /**
     * @param array $search
     * @return $this
     */
    public function whereRelation(array $search)
    {
        foreach (['category_id' => 'category', 'trademark_id' => 'trademark'] as $column => $relation) {
            $value = $search[$column] ?? null;

            $this->query = $this->query->when(isNotEmptyStringOrNull($value), function ($q) use ($relation, $value) {
                $q->whereHas($relation, function ($e) use ($value) {
                    $e->where('id', $value);
                });
            });
        }

        return $this;
    }

    /**
     * @param \Illuminate\Http\Request $search
     * @return $this
     */
    public function with($request = null)
    {
        $this->query = $this->query->with('category', 'trademark', 'media');

        return $this;
    }

    /**
     * @return array
     */
    public function sort()
    {
        return ['id', 'name', 'price', 'quantity', 'status', 'created_at', 'updated_at'];
    }

    /**
     * @param \Illuminate\Http\Request $request
     * @return array
     */
    protected function getFormRequest(Request $request)
    {
        return $request->only([
            'name',
            'code',
            'category_id',
            'trademark_id',
            'quantity',
            'price',
            'currency',
            'origin',
            'status',
            'description',
        ]);
    }

    /**
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Database\Eloquent\Model|$this
     */
    public function create($request)
    {
        $this->newQuery();
        $attributes = $this->getFormRequest($request);
        $storePivots = [];

        if ($request->has('stores')) {
            foreach ($request->get('stores') as $value) {
                $storePivots[$value['id']] = ['status' => $value['status']];
            }
        }

        return DB::transaction(function () use ($request, $attributes, $storePivots) {
            $product = $this->query->create($attributes);
            throw_unless($product, \Exception::class, __('Create product failed.'), Response::HTTP_INTERNAL_SERVER_ERROR);

            if (!empty($storePivots)) {
                $product->stores()->sync($storePivots);
            }

            if ($request->hasFile('thumbnail')) {
                $this->attachMedia($request->file('thumbnail'), MediaTag::THUMBNAIL, $product);
            }

            if ($request->hasFile('media')) {
                $this->attachMedia($request->file('media'), MediaTag::MEDIA, $product);
            }

            return $product->load('category', 'trademark')->loadMedia();
        });
    }

    /**
     * @param \Illuminate\Http\Request $request
     * @param int $id
     * @return \Illuminate\Database\Eloquent\Model|$this
     */
    public function update($request, $id)
    {
        $this->newQuery();
        $attributes = $this->getFormRequest($request);
        $storePivots = [];

        if ($request->has('stores')) {
            foreach ($request->get('stores') as $value) {
                $storePivots[$value['id']] = ['status' => $value['status']];
            }
        }

        return DB::transaction(function () use ($request, $id, $attributes, $storePivots) {
            $product = $this->query->find($id);
            throw_unless($product->update($attributes), \Exception::class, __('Update product failed.'), Response::HTTP_INTERNAL_SERVER_ERROR);

            if (!empty($storePivots)) {
                $product->stores()->sync($storePivots);
            }

            if ($request->hasFile('thumbnail')) {
                $thumbnail = $product->firstMedia(MediaTag::THUMBNAIL);
                $this->attachMedia($request->file('thumbnail'), MediaTag::THUMBNAIL, $product);

                if ($thumbnail) {
                    try {
                        $this->fileService->delete($thumbnail->id);
                    } catch (\Exception $e) {
                        report($e);
                    }
                }
            }

            if ($request->hasFile('media')) {
                $this->attachMedia($request->file('media'), MediaTag::MEDIA, $product, false);
            }

            if ($request->has('files_delete')) {
                $product->media()->detach($request->get('files_delete'));
                try {
                    $this->fileService->delete($request->get('files_delete'));
                } catch (\Exception $e) {
                    report($e);
                }
            }

            return $product->load('category', 'trademark')->loadMedia();
        });
    }

    /**
     * @param mixed $file
     * @param string $tag
     * @param \App\Models\Product $product
     * @param bool $syncMedia
     * @return void
     */
    protected function attachMedia($file, string $tag, Product $product, bool $syncMedia = true)
    {
        $media = $this->fileService->store($file, FolderName::PRODUCT);
        if (is_array($media)) $media = array_map(fn ($item) => $item->getKey(), $media);

        throw_unless($media, \Exception::class, 'Upload files failed.', Response::HTTP_INTERNAL_SERVER_ERROR);

        if ($syncMedia) {
            $product->syncMedia($media, $tag);
        } else {
            $product->attachMedia($media, $tag);
        }
    }
}
