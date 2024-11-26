<?php

namespace App\Http\Resources\Frontend;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class CategoryResource extends JsonResource
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
            'slug' => $this->slug, 
            'name' => $this->name, 
            'image_path' => $this->image_full_path, 
            'status' => $this->status,
        ];
    }
}
