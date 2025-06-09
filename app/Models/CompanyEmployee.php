<?php
declare(strict_types=1);

namespace App\Models;

use App\Traits\Blameable;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\Pivot;

class CompanyEmployee extends Pivot
{
    use Blameable;
    use SoftDeletes;

    protected $table = 'company_employee';
    protected $fillable = [
        'company_id',
        'employee_id',
        'created_by',
        'updated_by',
        'deleted_by',
    ];
    protected $casts = [
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];
}
