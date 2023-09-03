<?php

namespace App\Services;

use App\Models\Media;
use App\RepositoryInterfaces\MediaRepositoryInterface;
use Illuminate\Support\Facades\Storage;
use MediaUploader;
use Illuminate\Support\Str;

class FileService
{
    /** @var int */
    const MAXIMUM_NAME_LENGTH = 255;

    /**
     * @param \App\RepositoryInterfaces\MediaRepositoryInterface $mediaRepository
     */
    public function __construct(
        protected MediaRepositoryInterface $mediaRepository
    ) {
        //
    }

    /**
     * @param \Illuminate\Http\UploadedFile|\Illuminate\Http\UploadedFile[] $file
     * @param string|null $directory
     * @return \App\Models\Media|\App\Models\Media[]|array|null
     */
    public function store($file, ?string $directory = null)
    {
        $result = null;

        if (is_array($file)) {
            foreach ($file as $fileUpload) {
                $result[] = $this->saveAs($fileUpload, $directory);
            }
        } else {
            $result = $this->saveAs($file, $directory);
        }

        return $result;
    }

    /**
     * @param int|array $mediaIds
     * @return mixed
     */
    public function delete($mediaIds)
    {
        $mediaIds = (array) $mediaIds;
        $media = $this->mediaRepository->getByIds($mediaIds);
        $filesDelete = [];

        foreach ($media as $item) {
            $filesDelete[] = $item->getDiskPath();
        }

        $result = $this->mediaRepository->delete($mediaIds);

        foreach ($filesDelete as $path) {
            try {
                Storage::delete($path);
            } catch (\Exception $e) {
                report($e);
            }
        }

        return $result;
    }

    /**
     * @param \Illuminate\Http\UploadedFile|\Illuminate\Http\UploadedFile[]|array|null $file
     * @param string|null $directory
     * @return \App\Models\Media
     */
    protected function saveAs($file, ?string $directory = null)
    {
        $filename = $this->makeFilename($file);
        $displayName = $this->makeDisplayName($file);

        $media = MediaUploader::fromSource($file)
            ->useFilename($filename)
            ->beforeSave(function (Media $model) use ($displayName) {
                $model->setAttribute('display_name', $displayName);
            });

        if (isNotEmptyStringOrNull($directory)) {
            $media = $media->toDirectory($directory);
        }

        return $media->upload();
    }

    /**
     * @param \Illuminate\Http\UploadedFile|\Illuminate\Http\UploadedFile[]|array|null $file
     * @return string
     */
    protected function makeDisplayName($file)
    {
        $extension = '.' . $file->getClientOriginalExtension();
        $name = $file->getClientOriginalName();
        if (strlen($name) + strlen($extension) > $this::MAXIMUM_NAME_LENGTH) {
            $name = substr($name, 0, $this::MAXIMUM_NAME_LENGTH - strlen($extension));
        }

        return $name . $extension;
    }

    /**
     * @param \Illuminate\Http\UploadedFile|\Illuminate\Http\UploadedFile[]|array|null $file
     * @return string
     */
    protected function makeFilename($file)
    {
        return Str::random(100) . time();
    }
}
