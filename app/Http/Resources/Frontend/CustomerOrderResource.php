<?php

namespace App\Http\Resources\Frontend;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class CustomerOrderResource extends JsonResource
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
            'order_no' => $this->order_no,
            'full_name' => $this->user->name,
            'email' => $this->user->email,
            'phone' => $this->user->phone,
            'price' => $this->price,
            'location' => $this->location,
            'detail' => $this->detail,
            
            'item_code' => $this->item_code ?? "",
            'item_name' => $this->item_name ?? "",
            $this->mergeWhen($this->car && $this->order_type === "book", fn () => [
                'item' => new CarDetailResource($this->car)
            ]),
            'link' => $this->link,
            'link_korea' => $this->link_korea,
            'created_at' => $this->created_at,
        ];
    }
}
