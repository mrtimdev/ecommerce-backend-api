<?php

namespace App\Http\Resources\Frontend;

use Carbon\Carbon;
use App\Models\ContactUs;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Laravel\Sanctum\PersonalAccessToken;
use Illuminate\Http\Resources\Json\JsonResource;

class CarDetailResource extends JsonResource
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
        $agency_contact = DB::table('agency_contact')->where('type', '=', 'khmer')->firstOrFail();
        return [
            'id' => $this->id,
            'listing_date' => $this->listing_date ? Carbon::createFromFormat('Y-m-d', $this->listing_date) : null,
            'sourced_link' => $this->sourced_link,
            'code' => $this->code,
            'name' => $this->name,
            'slug' => $this->slug,
            'plate_number' => $this->plate_number,
            'price' => number_format((float) $this->total_price, 2, '.', ''),
            'payment_details' => $this->paymentDetails(),
            'payment_terms' => $this->paymentTerms(),
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
            'featured_image' => $this->featured_image,
            'featured_image_full_path' => $this->featured_image_full_path,
            'galleries' => $this->gallery->map(function($item) {
                return $item->image_full_path;
            }),
            
            'hot_marks' => $this->hot_marks->map(function($item) {
                return $item->name;
            }),
            'options' => $this->getCarOptionsByGroup(),
            'featured_information' => $this->featureInformation(),
            'status' => $this->status,

            'agency_contact' => [
                'title' => $agency_contact->title,
                'name' => $agency_contact->name,
                'phone' => $agency_contact->phone,
                'telegram_link' => $agency_contact->telegram_link,
                'facebook_link' => $agency_contact->facebook_link,
                'whatapp_link' => $agency_contact->whatapp_link,
                'avatar' => $agency_contact->avatar ? asset('storage/' . $agency_contact->avatar) : asset('assets/images/user/no-avatar.png')
            ],
            
            'detail_link' => "https://reachautoimport.com/Cambodia/detail?c_slug=$this->slug&id=$this->id",
        
            'view_count' => $this->view_count,
            'like_count' => $this->like_count,
            'created_at' => $this->created_at->toIso8601String(),
            'updated_at' => $this->updated_at->toIso8601String(),

            'is_liked' => $is_liked,
        ];
        
    }

}
