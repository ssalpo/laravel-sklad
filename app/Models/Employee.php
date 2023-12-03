<?php

namespace App\Models;

use Dyrynda\Database\Support\CascadeSoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Employee extends Model
{
    use HasFactory, SoftDeletes, CascadeSoftDeletes;

    protected array $cascadeDeletes = ['employeeSalaries'];

    protected $fillable = [
        'name'
    ];

    public function employeeSalaries(): HasMany
    {
        return $this->hasMany(EmployeeSalary::class);
    }
}
