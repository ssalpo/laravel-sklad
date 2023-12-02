<?php

namespace App\Services;

use App\Models\Employee;

class EmployeeService
{
    public function store(array $data): Employee
    {
        return Employee::create($data);
    }

    public function update(int $id, array $data): Employee
    {
        $employee = Employee::findOrFail($id);

        $employee->update($data);

        return $employee;
    }

    public function delete(int $id): bool
    {
        return Employee::findOrFail($id)->delete();
    }
}
