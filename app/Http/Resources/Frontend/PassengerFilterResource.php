<?php

namespace App\Http\Resources\Frontend;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class PassengerFilterResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'name' => $this->no > 1 ? "$this->no Seats" : "$this->no Seat" ,
            'code' => $this->code,
            'count' => $this->cars_count,
        ];
    }
}
