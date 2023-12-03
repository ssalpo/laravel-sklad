<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Arr;

class EmployeeSalary extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'employee_id',
        'amount',
        'comment',
    ];

    public function scopeFilter($q, array $data): void
    {
        $q->when(isset($data['date']['year']), function ($q) use ($data) {
            $q->whereYear('created_at', $data['date']['year'])
                ->whereMonth('created_at', $data['date']['month'] + 1);
        });
    }
}
