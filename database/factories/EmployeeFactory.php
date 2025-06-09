<?php
declare(strict_types=1);

namespace Database\Factories;

use App\Models\User;
use App\Models\Company;
use App\Models\Employee;
use Illuminate\Support\Arr;
use App\Enums\Employee\TypeEmpEnum;
use App\Enums\Employee\PositionEmpEnum;
use App\Enums\Employee\StatusEmpEnum;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Employee>
 */
class EmployeeFactory extends Factory
{
    protected $model = Employee::class;

    /** @return array<string, mixed> */
    public function definition(): array
    {
        $type = Arr::random(TypeEmpEnum::cases());
        $position = Arr::random(PositionEmpEnum::cases());
        $status = Arr::random(StatusEmpEnum::cases());
        $dateOfBirth = $this->faker->dateTimeBetween('-50 years', '-18 years');
        $dateHired = $this->faker->dateTimeBetween($dateOfBirth, 'now');
        return [
            'name' => $this->faker->name,
            'employee_id' => $this->faker->unique()->numerify('EMP-#####'),
            'gander' => $this->faker->randomElement(['laki-laki', 'perempuan']),
            'birth_place' => $this->faker->city(),
            'date_of_birth' => $dateOfBirth,
            'email' => $this->faker->unique()->safeEmail,
            'address' => $this->faker->address,
            'country' => $this->faker->country,
            'postal_code' => $this->faker->postcode,
            'date_hired' => $dateHired,
            'position' => $position->value,
            'type' => $type->value,
            'cv' => $this->faker->optional()->url,
            'photo' => $this->faker->imageUrl(),
            'hourly_rate' => $this->faker->randomFloat(2, 0, 100),
            'contract' => $this->faker->optional()->url,
            'whatsapp' => $this->faker->phoneNumber,
            'facebook' => $this->faker->optional()->url,
            'instagram' => $this->faker->optional()->url,
            'youtube' => $this->faker->optional()->url,
            'website' => $this->faker->optional()->url,
            'custom_link' => $this->faker->optional()->url,
            'status' => $status->value,
            'description' => $this->faker->text,
            'user_id' => User::factory(),
            'created_by' => null,
            'updated_by' => null,
        ];
    }

    public function withCompanies(int $count = 1): static
    {
        return $this->afterCreating(function (Employee $employee) use ($count) {
            $companies = Company::inRandomOrder()->limit($count)->get();

            // Kalau belum ada company, buat baru
            if ($companies->isEmpty()) {
                $companies = Company::factory()->count($count)->create();
            }

            $employee->companies()->syncWithoutDetaching(
                $companies->pluck('id')->map(fn($id) => (string) $id)->all()
            );
        });
    }
}
