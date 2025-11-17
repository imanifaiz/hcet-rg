<?php

namespace Database\Factories;

use App\Models\Company;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Str;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Company>
 */
class CompanyFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $company = $this->faker->unique()->company;
        return [
            'name' => $company,
            'email' => $this->faker->companyEmail,
            'website' => $this->faker->url,
            'logo' => UploadedFile::fake()
                ->image(
                    Str::of($company)->replace([' ', '-', '.'], '_')->lower() .'_logo.jpg',
                    600,
                    400
                )
                ->store('logos', 'public'),
        ];
    }
}
