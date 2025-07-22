<?php

namespace App\Http\Resources;

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
            'id'            => $this->id,
            'code'          => $this->code,
            'name'          => $this->name,
            'price'         => $this->price,
            'quantity'      => $this->quantity,
            'stock_alert'   => $this->stock_alert,
            'is_active'     => $this->is_active,
            'description'   => $this->description,
            'category_name' => optional($this->category)->name,
            'unit_name'     => optional($this->unit)->name,
            'images'        => $this->images->pluck('image_full_path')->toArray(),
        ];
    }
}
