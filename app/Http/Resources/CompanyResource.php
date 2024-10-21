<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class CompanyResource extends JsonResource
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
            'company_name' => $this->company_name,
            'ruc' => $this->ruc,
            'address' => $this->address,
            'email' => $this->email,
            'phone' => $this->phone,
            'status' => $this->status,
            'logo_path' => $this->logo_path,
            'sol_user' => $this->sol_user,
            'sol_pass' => $this->sol_pass,
            'cert_path' => $this->cert_path,
            'client_id' => $this->cliente_id,
            'client_secret' => $this->client_secret,
            'production' => $this->production,
        ];
    }
}
