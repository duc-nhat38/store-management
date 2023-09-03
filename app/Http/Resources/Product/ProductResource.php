<?php

namespace App\Http\Resources\Product;

use App\Enums\MediaTag;
use App\Http\Resources\Category\CategoryResource;
use App\Http\Resources\Media\MediaResource;
use App\Http\Resources\Trademark\TrademarkResource;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ProductResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'code' => $this->code,
            'name' => $this->name,
            'category' => CategoryResource::make($this->category),
            'trademark' => TrademarkResource::make($this->trademark),
            'thumbnail' => MediaResource::make($this->firstMedia(MediaTag::THUMBNAIL)),
            'media' => MediaResource::collection($this->getMedia(MediaTag::MEDIA)),
            'quantity' => $this->quantity,
            'price' => $this->price,
            'currency' => $this->currency,
            'origin' => $this->origin,
            'status' => $this->status,
            'description' => $this->description,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];
    }
}
