<?php

namespace App\Http\Resources\Frontend;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ContactUsResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'phone_number' => $this->phone_number,
            'telegram' => $this->telegram,
            'telegram_channel' => $this->telegram_channel,
            'facebool_page' => $this->facebool_page,
            'youtube' => $this->youtube,
            'tiktok' => $this->tiktok,
            'address' => $this->address,
            'image_path' => $this->image_full_path,
        ];
    }
}
