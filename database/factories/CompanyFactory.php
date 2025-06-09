<?php
declare(strict_types=1);

namespace Database\Factories;

use App\Models\Company;
use Illuminate\Support\Arr;
use Illuminate\Support\Str;
use App\Enums\Company\TypeCompany;
use App\Enums\Company\StatusCompany;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Company>
 */
class CompanyFactory extends Factory
{
    protected $model = Company::class;

    /** @return array<string, mixed> */
    public function definition(): array
    {
        $user = \App\Models\User::inRandomOrder()->first() ?? \App\Models\User::factory()->create();
        $statusCompany = Arr::random(StatusCompany::cases());
        $typeCompany = Arr::random(TypeCompany::cases());
        $startDate = $this->faker->dateTimeBetween('-1 year', '+1 year');
        $endDate = $this->faker->dateTimeBetween($startDate, '+1 year');
        $endDate = $statusCompany === StatusCompany::Active ? $endDate : null;
        $startDate = $statusCompany === StatusCompany::Active ? $startDate : null;
        $endDate = $typeCompany === TypeCompany::Mitra ? $endDate : null;
        $startDate = $typeCompany === TypeCompany::Mitra ? $startDate : null;
        return [
            'id' => Str::uuid(),
            'name' => $this->faker->company(),
            'slug' => $this->faker->unique()->slug(),
            'email' => $this->faker->unique()->safeEmail(),
            'phone' => $this->faker->phoneNumber(),
            'address' => $this->faker->address(),
            'city' => $this->faker->city(),
            'region' => $this->faker->state(),
            'country' => $this->faker->country(),
            'postal_code' => $this->faker->postcode(),
            'website' => $this->faker->url(),
            'instagram' => $this->faker->userName() . '/' . $this->faker->userName(),
            'facebook' => $this->faker->userName() . '/' . $this->faker->userName(),
            'youtube' => $this->faker->userName() . '/' . $this->faker->userName(),
            'custom_link' => $this->faker->url(),
            'logo' => $this->faker->optional()->imageUrl(100, 100, 'business', true, 'logo'), // Generates a random image URL.,
            'notes' => $this->faker->optional()->text,
            'status' => $statusCompany->value,
            'start_date' => $startDate,
            'end_date' => $endDate,
            'type' => $typeCompany->value,
            'created_by' => $user->id ?? null,
            'updated_by' => $user->id ?? null,
            'created_at' => now(),
            'updated_at' => now(),
        ];
    }

    public function active(): self
    {
        return $this->state(fn() => ['status' => StatusCompany::Active->value]);
    }


    public function withEmployees(int $count = 3): self
    {
        return $this->afterCreating(function (Company $company) use ($count) {
            $employees = \App\Models\Employee::factory()->count($count)->create();
            $company->emps()->syncWithoutDetaching(
                $employees->pluck('id')->map(fn($id) => (string) $id)->all()
            );
        });
    }

    public function forUser($userId)
    {
        $this->userId = $userId;
        return $this;
    }
}
