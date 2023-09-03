<?php

namespace App\Http\Resources\Category;

use App\Http\Resources\BaseResourceCollection;
use Illuminate\Http\Request;

class CategoryCollection extends BaseResourceCollection
{
    /**
     * Transform the resource collection into an array.
     *
     * @return array<int|string, mixed>
     */
    public function toArray(Request $request): array
    {
        return parent::toArray($request);
    }
}
