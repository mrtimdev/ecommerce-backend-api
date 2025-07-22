<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class PartialProductResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id'            => $this->id,
            'code'          => $this->code,
            'name'          => $this->name,
            'unit_price'         => $this->price,
            'real_unit_price'         => $this->price,
            'quantity'      => $this->quantity,
            'category' => optional($this->category)->name,
            'unit'     => optional($this->unit)->name,
        ];
    }
}
