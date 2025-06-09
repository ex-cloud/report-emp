<?php

namespace App\Observers;

use App\Enums\Employee\TypeEmpEnum;
use App\Models\Employee;

class EmployeeObserver
{
    public function creating(Employee $employee): void
    {
        // $prefix = strtoupper($employee->type ?? TypeEmpEnum::EMP->value); // EMP atau FREE
        $prefix = strtoupper(
            $employee->type instanceof TypeEmpEnum
                ? $employee->type->value
                : ($employee->type ?? TypeEmpEnum::EMP->value)
        );

        $date = now()->format('dmY');
        $countToday = Employee::whereDate('created_at', now())->count() + 1;
        $sequence = str_pad($countToday, 3, '0', STR_PAD_LEFT);

        $employee->employee_id = "{$prefix}-{$date}-{$sequence}";
    }
}
