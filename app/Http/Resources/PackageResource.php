<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use App\Http\Resources\PartialUserResource;
use App\Http\Resources\PartialCustomerResource;
use Illuminate\Http\Resources\Json\JsonResource;
use App\Http\Resources\PartialDeliveryItemResource;

class PackageResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id'               => $this->id,
            'date'             => $this->date,
            'reference_no'     => $this->reference_no,
            'client_id'        => $this->client_id,
            'customer_id'      => $this->customer_id,
            'address'          => $this->address,
            'note'             => $this->note,
            'status'           => $this->status,
            'delivery_status'  => $this->delivery_status,
            'image_path'       => $this->image_path,
            'created_at'       => $this->created_at,
            'updated_at'       => $this->updated_at,

            // Related data
            'client'   => new PartialUserResource($this->whenLoaded('user')),
            'customer' => new PartialCustomerResource($this->whenLoaded('customer')),
            'items'            => PartialDeliveryItemResource::collection($this->whenLoaded('items')),
        ];
    }
}
