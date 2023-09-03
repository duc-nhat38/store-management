<?php

namespace App\Http\Resources\Trademark;

use App\Http\Resources\BaseResourceCollection;
use Illuminate\Http\Request;
class TrademarkCollection extends BaseResourceCollection
{
    /**
     * Transform the resource collection into an array.
     *
     * @return array<int|string, mixed>
     */
    public function toArray(Request $request): array
    {
        return $this->resource->map(fn ($item) => [
            'id' => $item->id,
            'name' => $item->name,
            'nation' => $item->nation,
        ])->toArray();
    }
}
