<?php

namespace App\Http\Resources\Frontend\HomePage;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\ResourceCollection;

class SliderCollection extends ResourceCollection
{
    /**
     * Transform the resource collection into an array.
     *
     * @return array<int|string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'data' => $this->collection->map(function ($slider) {
                return new SliderResource($slider);
            })
        ];
    }
}
