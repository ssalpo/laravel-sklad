<?php

namespace App\Http\Controllers\Admin\Employee;

use App\Http\Controllers\Controller;
use App\Http\Requests\Employee\EmployeeRequest;
use App\Models\Employee;
use App\Services\EmployeeService;
use App\Services\Toast;
use Illuminate\Http\RedirectResponse;

class EmployeeController extends Controller
{
    public function __construct(
        protected EmployeeService $employeeService
    )
    {
    }

    public function index()
    {
        $employees = Employee::orderBy('created_at', 'DESC')
            ->paginate()
            ->onEachSide(0)
            ->through(fn($m) => [
                'id' => $m->id,
                'name' => $m->name,
                'created_at' => $m->created_at->format('d-m-Y H:i'),
            ]);

        return inertia('Employees/Index', compact('employees'));
    }

    public function create()
    {
        return inertia('Employees/Edit');
    }

    public function store(EmployeeRequest $request)
    {
        $this->employeeService->store($request->validated());

        Toast::success('Успешно добавлено.');

        return to_route('employees.index');
    }

    public function edit(Employee $employee)
    {
        return inertia('Employees/Edit', compact('employee'));
    }

    public function update(int $id, EmployeeRequest $request): RedirectResponse
    {
        $this->employeeService->update($id, $request->validated());

        Toast::success('Данные успешно изменены.');

        return to_route('employees.index');
    }

    public function destroy(int $id): RedirectResponse
    {
        $this->employeeService->delete($id);

        Toast::success('Успешно удалено.');

        return to_route('employees.index');
    }
}
