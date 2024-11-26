<?php

namespace App\Http\Resources\Frontend;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use App\Http\Resources\Frontend\CarGalleryCollection;
use Carbon\Carbon;

class CarResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public $preserveKeys = false;
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'code' => $this->code,
            'name' => $this->name,
            'slug' => $this->slug,
            'plate_number' => $this->plate_number,
            'current_price' => number_format((float) $this->current_price, 2, '.', ''),
            'previous_price' => number_format((float) $this->previous_price, 2, '.', ''),
            'total_price' => $this->total_price,
            'payment_details' => $this->paymentDetails(),
            'year' => $this->year,
            'mileage' => $this->mileage,
            'description' => $this->description,
            'featured_image' => $this->featured_image,
            'featured_image_full_path' => $this->featured_image_full_path,
            'is_featured' => $this->is_featured,
            'is_active' => $this->is_active,
            'steering' => $this->steering,
            'engine_volume' => $this->engine_volume . " CC",
            'door' => $this->door,
            
            'cylinder' => $this->cylinder,
            'engine_power' => $this->engine_power . " HP",
            'odometer_reading' => $this->odometer_reading,
            'water_flood_damaged' => $this->water_flood_damaged,
            'former_rental_car' => $this->former_rental_car,
            'former_taxi' => $this->former_taxi,
            'recovered_theft' => $this->recovered_theft,
            'police_car' => $this->police_car,
            'salvage_record' => $this->salvage_record,
            'fuel_conversion' => $this->fuel_conversion,
            'modified_seats' => $this->modified_seats,
            'first_registered_date' => Carbon::createFromFormat('Y-m-d', $this->first_registered_date),
        
            // Relationships
            'category' => new CategoryResource($this->category),
            'condition' => new ConditionResource($this->condition),
            'brand' => new BrandResource($this->brand),
            'model' => new ModelResource($this->model),
            'fuel_type' => new FuelTypeResource($this->fuelType),
            'transmission_type' => new TransmissionTypeResource($this->transmissionType),
            'color' => new ColorResource($this->color),
            'passenger' => $this->passenger,
            'drive_type' => $this->driveType,
            'size' => $this->size,
        
            // Additional data
            
            'gallery' => new CarGalleryCollection($this->gallery),
            
            'status' => $this->status,
        
            // Timestamps
            'created_at' => $this->created_at->toIso8601String(),
            'updated_at' => $this->updated_at->toIso8601String(),
        ];
        
    }
}
