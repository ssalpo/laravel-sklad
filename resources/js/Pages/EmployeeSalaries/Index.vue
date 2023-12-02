<template>
    <Head>
        <title>Платежи сотрудника</title>
    </Head>

    <div class="content-header">
        <div class="container-fluid">
            <h1 class="m-0">Платежи сотрудника</h1>
        </div>
    </div>

    <div class="content">
        <div class="container-fluid">
            <div class="card">
                <div class="card-header">
                    <div class="card-tools">
                        <Link :href="route('employees.employee-salaries.create', {employee: employeeId})" class="btn btn-success btn-sm px-3">
                            Новый платеж
                        </Link>
                    </div>
                </div>
                <!-- /.card-header -->
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-bordered  text-nowrap">
                            <thead>
                            <tr>
                                <th style="width: 10px">#</th>
                                <th>Дата добавления</th>
                                <th>Сумма</th>
                                <th>Комментарий</th>
                                <th width="40"></th>
                            </tr>
                            </thead>
                            <tbody>
                            <tr v-for="(employeeSalary, index) in employeeSalaries.data">
                                <td :data-id="employeeSalary.id">{{ ((employeeSalaries.current_page - 1) * employeeSalaries.per_page) + index + 1 }}</td>
                                <td>{{employeeSalary.created_at}}</td>
                                <td>{{employeeSalary.amount}} сом.</td>
                                <td>{{employeeSalary.comment}}</td>
                                <td class="text-center">
                                    <Link class="btn btn-sm btn-outline-primary mr-2" :href="route('employees.employee-salaries.edit', { employee: employeeId, employee_salary: employeeSalary.id })">
                                        <i class="fa fa-pencil-alt"></i>
                                    </Link>

                                    <delete-btn
                                        :url="route('employees.employee-salaries.destroy', { employee: employeeId, employee_salary: employeeSalary.id })"
                                    />
                                </td>
                            </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
                <!-- /.card-body -->

                <div class="card-footer clearfix" v-if="employeeSalaries.links.length > 3">
                    <pagination :links="employeeSalaries.links"/>
                </div>
            </div>
        </div>
    </div>
</template>
<script>
import {Head, Link} from "@inertiajs/inertia-vue3";
import Pagination from "../../Shared/Pagination.vue";
import DeleteBtn from "../../Shared/DeleteBtn.vue";

export default {
    components: {DeleteBtn, Pagination, Head, Link},
    props: ['employeeSalaries', 'employeeId'],
}
</script>
