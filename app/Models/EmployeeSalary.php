<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

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
        $q->when(isset($data['date'][0], $data['date'][1]), function ($q) use ($data) {
            $q->whereDate('created_at', '>=', $data['date'][0])
                ->whereDate('created_at', '<=', $data['date'][1]);
        });
    }
}
