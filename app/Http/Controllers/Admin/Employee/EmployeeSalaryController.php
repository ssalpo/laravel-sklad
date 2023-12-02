<?php

namespace App\Http\Controllers\Admin\Employee;

use App\Http\Controllers\Controller;
use App\Http\Requests\Employee\EmployeeSalaryRequest;
use App\Models\EmployeeSalary;
use App\Services\EmployeeSalaryService;
use App\Services\Toast;
use Illuminate\Http\RedirectResponse;

class EmployeeSalaryController extends Controller
{
    public function __construct(
        protected EmployeeSalaryService $employeeSalaryService
    )
    {
    }

    public function index(int $employeeId)
    {
        $employeeSalaries = EmployeeSalary::orderBy('created_at', 'DESC')
            ->paginate()
            ->onEachSide(0)
            ->through(fn($m) => [
                'id' => $m->id,
                'amount' => $m->amount,
                'comment' => $m->comment,
                'created_at' => $m->created_at->format('d-m-Y H:i'),
            ]);

        return inertia('EmployeeSalaries/Index', compact('employeeId', 'employeeSalaries'));
    }

    public function create(int $employeeId)
    {
        return inertia('EmployeeSalaries/Edit', compact('employeeId'));
    }

    public function store(int $employeeId, EmployeeSalaryRequest $request)
    {
        $this->employeeSalaryService->store($request->validated());

        Toast::success('Успешно добавлено.');

        return to_route('employees.employee-salaries.index', $employeeId);
    }

    public function edit(int $employeeId, EmployeeSalary $employeeSalary)
    {
        return inertia('EmployeeSalaries/Edit', compact('employeeId', 'employeeSalary'));
    }

    public function update(int $employeeId, int $id, EmployeeSalaryRequest $request): RedirectResponse
    {
        $this->employeeSalaryService->update($id, $request->validated());

        Toast::success('Данные успешно изменены.');

        return to_route('employees.employee-salaries.index', $employeeId);
    }

    public function destroy(int $employeeId, int $id): RedirectResponse
    {
        $this->employeeSalaryService->delete($id);

        Toast::success('Успешно удалено.');

        return to_route('employees.employee-salaries.index', $employeeId);
    }
}
