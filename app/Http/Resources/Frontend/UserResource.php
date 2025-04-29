<?php

namespace App\Http\Resources\Frontend;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class UserResource extends JsonResource
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
            'full_name' => $this->name,
            'username' => $this->username,
            'email' => $this->email,
            'setting' => [
                'first_name' => $this->first_name,
                'last_name' => $this->last_name,
                'email' => $this->email_2,
                'phone' => $this->phone,
                'dob' => $this->dob,
                'gender' => $this->gender,
                'company' => $this->company,
                'address' => $this->address,
                'avatar' => $this->avatar_full_path,
                'cover' => $this->cover_full_path,
            ],
            
        ];
    }
}
