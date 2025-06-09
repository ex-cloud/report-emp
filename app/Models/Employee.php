<?php
declare(strict_types=1);

namespace App\Models;

use App\Traits\HasUuid;
use App\Traits\Blameable;
use App\Enums\Employee\TypeEmpEnum;
use App\Enums\Employee\StatusEmpEnum;
use App\Enums\Employee\PositionEmpEnum;
use Database\Factories\EmployeeFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

/**
 * @use HasFactory<EmployeeFactory>
 * @property string $id
 * @property string $name
 * @property string $employee_id
 * @property string $gander
 * @property string $birth_place
 * @property string $status
 * @property string $date_of_birth
 * @property string $email
 * @property string $address
 * @property string $country
 * @property null|string $postal_code
 * @property string $date_hired
 * @property string $position
 * @property string $type
 * @property null|string $cv
 * @property null|string $photo
 * @property null|string $hourly_rate
 * @property null|string $contract
 * @property null|string $whatsapp
 * @property null|string $facebook
 * @property null|string $instagram
 * @property null|string $youtube
 * @property null|string $website
 * @property null|string $custom_link
 * @property null|string $description
 * @property null|string $company_id
 * @property null|string $user_id
 * @property null|\Carbon\CarbonInterface $created_at
 * @property null|\Carbon\CarbonInterface $updated_at
 * @property null|string $created_by
 * @property null|string $updated_by
 * @property null|string $deleted_by
 * @var string $photo_url
 * 
 */
class Employee extends Model
{
    use HasFactory;
    use SoftDeletes;
    use HasUuid;
    use Blameable;
    
    protected $table = 'employees';
    protected $fillable = [
        'name',
        'employee_id',
        'gender',
        'birth_place',
        'date_of_birth',
        'email',
        'address',
        'country',
        'postal_code',
        'date_hired',
        'position',
        'type',
        'status',
        'cv',
        'photo',
        'hourly_rate',
        'contract',
        'whatsapp',
        'facebook',
        'instagram',
        'youtube',
        'website',
        'custom_link',
        'description',

        // Foreign keys
        'company_id',
        'user_id',
        'created_by',
        'updated_by',
        'deleted_by',
    ];

    protected $casts = [
        'date_of_birth' => 'date',
        'date_hired' => 'date',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
        'position' => PositionEmpEnum::class,
        'type' => TypeEmpEnum::class,
        'status' => StatusEmpEnum::class,
    ];

    public function getPhotoUrlAttribute()
    {
        return $this->photo
            ? asset('storage/employees/photos' . $this->photo)
            : asset('assets/placeholder.png');
    }

    public function scopeSelfEditable(Builder $query): Builder
    {
        // Memastikan user hanya bisa mengedit dirinya sendiri
        return $query->where('id', auth()->id());
    }

    public function companies(): BelongsToMany
    {
        return $this->belongsToMany(Company::class, 'company_employee', 'employee_id', 'company_id')
            ->using(CompanyEmployee::class)
            ->withPivot('created_by', 'updated_by')
            ->withTimestamps();
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }
}
