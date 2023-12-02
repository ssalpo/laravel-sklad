<?php

namespace App\Http\Controllers\Admin\Employee;

use App\Http\Controllers\Controller;
use App\Http\Requests\Employee\EmployeeSalaryRequest;
use App\Models\Employee;
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

    public function index(Employee $employee)
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

        return inertia('EmployeeSalaries/Index', compact('employee', 'employeeSalaries'));
    }

    public function create()
    {
        return inertia('EmployeeSalaries/Edit');
    }

    public function store(EmployeeSalaryRequest $request)
    {
        $this->employeeSalaryService->store($request->validated());

        Toast::success('Успешно добавлено.');

        return to_route('employees.index');
    }

    public function edit(Employee $employee)
    {
        return inertia('EmployeeSalaries/Edit', compact('employee'));
    }

    public function update(int $id, EmployeeSalaryRequest $request): RedirectResponse
    {
        $this->employeeSalaryService->update($id, $request->validated());

        Toast::success('Данные успешно изменены.');

        return to_route('employees.index');
    }

    public function destroy(int $id): RedirectResponse
    {
        $this->employeeSalaryService->delete($id);

        Toast::success('Успешно удалено.');

        return to_route('employees.index');
    }
}
