<?php

namespace App\Http\Resources\Frontend;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class StoryResource extends JsonResource
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
            'youtube_video_id' => extractYouTubeVideoId($this->youtube_link),
            'image_url' => $this->image_full_path,
            'country' => $this->country,
            'view_count' => $this->view,
            'like_count' => $this->like,
            'is_active' => $this->is_active,
            'created_at' => $this->created_at,
        ];
    }
}
