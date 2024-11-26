<?php

namespace App\Http\Resources\Frontend;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class MenuResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'code' => $this->code,
            'name' => $this->name,
            'slug' => $this->slug,
            'items' => [
                'brands' => $this->brands->map(function($item) {
                    return [
                        'code' => $item->code,  
                        'name' => $item->name, 
                    ];
                }),
                'models' => $this->models->map(function($item) {
                    return [
                        'code' => $item->code,  
                        'name' => $item->name, 
                    ];
                }),
                'categories' => $this->categories->map(function($item) {
                    return [
                        'code' => $item->code,  
                        'name' => $item->name, 
                    ];
                }),
                'fuel_types' => $this->fuel_types->map(function($item) {
                    return [
                        'code' => $item->code,  
                        'name' => $item->name, 
                    ];
                }),
                'steerings' => $this->steerings->map(function($item) {
                    return [
                        'code' => $item->code,  
                        'name' => $item->name, 
                        'description' => $item->description, 
                    ];
                }),
                'drive_types' => $this->drive_types->map(function($item) {
                    return [
                        'code' => $item->code,  
                        'name' => $item->name, 
                    ];
                }),
                'passengers' => $this->passengers->map(function($item) {
                    return [
                        'code' => $item->no,  
                        'name' => $item->no, 
                    ];
                }),
                'locations' => $this->locations->map(function($item) {
                    return [
                        'code' => $item->code, 
                        'name' => $item->name, 
                    ];
                })
            ]
        ];
    }
}
