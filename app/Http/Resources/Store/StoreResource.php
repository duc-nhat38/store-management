<?php

namespace App\Http\Resources\Store;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class StoreResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string=> $this->name, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'email' => $this->email,
            'phone_number' => $this->phone_number,
            'address' => $this->address,
            'fax' => $this->fax,
            'operation_start_date' => $this->operation_start_date,
            'number_of_employees' => $this->number_of_employees,
            'status' => $this->status,
            'note' => $this->note,
        ];
    }
}
