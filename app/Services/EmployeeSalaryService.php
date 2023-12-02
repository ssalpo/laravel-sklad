<?php

namespace App\Services;

use App\Models\EmployeeSalary;

class EmployeeSalaryService
{
    public function store(array $data): EmployeeSalary
    {
        return EmployeeSalary::create($data);
    }

    public function update(int $id, array $data): EmployeeSalary
    {
        $employeeSalary = EmployeeSalary::findOrFail($id);

        $employeeSalary->update($data);

        return $employeeSalary;
    }

    public function delete(int $id): bool
    {
        return EmployeeSalary::findOrFail($id)->delete();
    }
}
