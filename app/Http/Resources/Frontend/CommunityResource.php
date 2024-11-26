<?php

namespace App\Http\Resources\Frontend;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class CommunityResource extends JsonResource
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
            'button_label' => $this->button_label,
            'button_url' => $this->button_url,
            'description' => $this->description,
            'items' => CommunityItemResource::collection($this->communityItems)
        ];
    }
}
