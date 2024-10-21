<?php

namespace App\Http\Resources;

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
            'name' => $this->name,
            'dni' => $this->dni,
            'phone' => $this->phone,
            'url_photo_profile' => $this->url_photo_profile,
            'email' => $this->email,
            'password' => $this->password,
            'company_id' => $this->company_id,
            'parameter' => $this->whenLoaded('parameter') && $this->parameter->isNotEmpty()
                ? new ParameterResource($this->parameter->first())
                : null,
            'company' => new CompanyResource($this->whenLoaded('company')),
            'role' => $this->whenLoaded('roles') && $this->roles->isNotEmpty()
                ? new RoleResource($this->roles->first())
                : null,
            'is_active' => (bool) $this->is_active,
        ];
    }
}
