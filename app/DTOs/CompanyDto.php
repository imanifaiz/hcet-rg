<?php

namespace App\DTOs;

use Illuminate\Contracts\Support\Arrayable;
use Illuminate\Http\Request;
use Illuminate\Http\UploadedFile;

class CompanyDto implements Arrayable
{
    public function __construct(
        public string $name,
        public ?string $email,
        public ?UploadedFile $logo,
        public ?string $website,
    )
    {}

    public static function fromRequest(Request $request): self
    {
        return new static(
            $request->name,
            $request->email,
            $request->file('logo'),
            $request->website,
        );
    }

    public static function fromArray(array $payload): self
    {
        return new static(
            $payload['name'],
            $payload['email'] ?? '',
            $payload['logo'] ?? null,
            $payload['website'] ?? '',
        );
    }

    public function toArray(): array
    {
        return [
            'name' => $this->name,
            'email' => $this->email,
            'logo' => $this->logo,
            'website' => $this->website,
        ];
    }
}
