<?php

namespace App\Http\Resources\Media;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class MediaResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            "id" => $this->id,
            "display_name" => $this->display_name,
            "extension" => $this->extension,
            "mime_type" => $this->mime_type,
            "aggregate_type" => $this->aggregate_type,
            "size" => $this->size,
            'url' => $this->getUrl(),
        ];
    }
}
