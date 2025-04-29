<?php

namespace App\Http\Resources\Frontend\HomePage;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class SliderResource extends JsonResource
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
            'title' => $this->title,
            'description' => $this->description,
            'image_url' => $this->image_path ? asset('storage/' . $this->image_path) : null,
            'image_url_2' => $this->image_path_2 ? asset('storage/' . $this->image_path_2) : null,
            'link' => $this->link ? $this->link : null,
            'is_active' => $this->is_active ? true : false,
        ];
    }
}
