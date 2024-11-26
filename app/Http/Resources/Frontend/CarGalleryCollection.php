<?php

namespace App\Http\Resources\Frontend;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\ResourceCollection;

class CarGalleryCollection extends ResourceCollection
{
    /**
     * Transform the resource collection into an array.
     *
     * @return array<int|string, mixed>
     */
    public function toArray(Request $request)
    {
        return $this->collection->map(function ($item) {
            return [
                'id' => $item->id,
                'image_full_path' => $item->image_full_path,
            ];
        }); 
    }
}
