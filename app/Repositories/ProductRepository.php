<?php

namespace App\Repositories;

use App\Enums\FolderName;
use App\Enums\MediaTag;
use App\Models\Product;
use App\RepositoryInterfaces\ProductRepositoryInterface;
use App\Services\FileService;
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
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Database\Eloquent\Model|$this
     */
    public function create($request)
    {
        $this->newQuery();
        $attributes = $request->only([
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
        $storePivots = [];

        if ($request->has('stores')) {
            foreach ($request->get('stores') as $value) {
                $storePivots[$value['id']] = ['status' => $value['status']];
            }
        }

        return DB::transaction(function () use ($request, $attributes, $storePivots) {
            $product = $this->query->create($attributes);
            throw_unless($product, \Exception::class, __('Create product failed.'), Response::HTTP_INTERNAL_SERVER_ERROR);

            if (empty($storePivots)) {
                $product->stores()->sync($storePivots);
            }

            if ($request->hasFile('thumbnail')) {
                $this->attachMedia($request->file('thumbnail'), MediaTag::THUMBNAIL, $product);
            }

            if ($request->hasFile('media')) {
                $this->attachMedia($request->file('media'), MediaTag::MEDIA, $product);
            }

            return $product;
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
