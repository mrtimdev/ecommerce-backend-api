<?php

namespace App\Http\Resources\Frontend;

use Carbon\Carbon;
use App\Models\ContactUs;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Laravel\Sanctum\PersonalAccessToken;
use Illuminate\Http\Resources\Json\JsonResource;

class ClientCarDetailResource extends JsonResource
{
    public $preserveKeys = false;
    public function toArray(Request $request): array
    {
        $is_liked = false;
        $token = $request->bearerToken();
        $tokenModel = PersonalAccessToken::findToken($token);
        if ($tokenModel) {
            $user = $tokenModel->tokenable;
            if($user && $user->id) {
                $is_liked = $this->likes()->where('user_id', $user->id)->exists();
            }
        }
        return [
            'id' => $this->id,
            'created_at' => $this->created_at->toIso8601String(),
            'code' => $this->code,
            'name' => $this->name,
            'slug' => $this->slug,
            'plate_number' => $this->plate_number,
            'price' => number_format((float) $this->price, 2, '.', ''),
            'car_price' => number_format((float) $this->car_price, 2, '.', ''),
            'total_price' => number_format((float) $this->total_price, 2, '.', ''),
            'discount' => $this->discount,
            'year' => $this->year,
            'mileage' => number_format((float) $this->mileage, 0, '.', ',') . " Km",
            'description' => ($this->description),

            'is_featured' => $this->is_featured,
            'is_active' => $this->is_active,
            'steering' => $this->steering->name,
            'engine_volume' => number_format((float) $this->engine_volume, 0, '.', ',') . " CC",
            'door' => $this->door,

            'cylinder' => $this->cylinder,
            'engine_power' => number_format((float) $this->engine_power, 0, '.', ',') . " HP",
            'odometer_reading' => $this->odometer_reading,
            'first_registered_date' => $this->first_registered_date ? Carbon::createFromFormat('Y-m-d', $this->first_registered_date) : null,

            // Relationships
            'category' => $this->category->name,
            'condition' => $this->condition->name,
            'brand' => $this->brand->name,
            'model' => $this->model->name,
            'fuel_type' => $this->fuelType->name,
            'transmission_type' => $this->transmissionType->name,
            'color' => [
                'hex' => $this->color->hex,
                'code' => $this->color->code,
                'name' => $this->color->name,
            ],
            'number_of_passenger' => $this->passenger->no,
            'drive_type' => $this->driveType->name,
            'size' => $this->size ?? "",
            'location' => [
                'code' => $this->location->code,
                'flag_code' => $this->location->flag_code,
                'dial_code' => $this->location->dial_code,
                'name' => $this->location->name,
                'flag_url' => $this->location->flag_url,
            ],

            // Additional data
            'youtube_video_id' => extractYouTubeVideoId($this->youtube_link),
            'galleries' => $this->gallery->map(function($item) {
                return $item->image_full_path;
            }),
            'featured_information' => $this->featureInformation(),
            'status' => $this->status,

            'detail_link' => "https://reachautoimport.com/Cambodia/detail?c_slug=$this->slug&id=$this->id",

            'view_count' => $this->view_count,
            'like_count' => $this->like_count,
            'created_at' => $this->created_at->toIso8601String(),
            'updated_at' => $this->updated_at->toIso8601String(),

            'is_liked' => $is_liked,
            'client' => $this->client
        ];

    }

}
