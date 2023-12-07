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
                <div class="card-header d-print-none">
                    <div class="float-left">
                        <back-btn :href="route('employees.index')" />
                    </div>
                    <div class="float-right">
                        <Link :href="route('employees.employee-salaries.create', {employee: employeeId})" class="btn btn-success btn-sm px-3">
                            Новый платеж
                        </Link>
                    </div>
                </div>
                <!-- /.card-header -->
                <div class="card-body">

                    <filters
                        class="d-print-none"
                        :employee-id="employeeId"
                        :filter-params="filterParams"
                    />

                    <div class="table-responsive">
                        <table class="table table-bordered  text-nowrap">
                            <thead>
                            <tr>
                                <th style="width: 10px">#</th>
                                <th>Дата добавления</th>
                                <th>Сумма</th>
                                <th>Комментарий</th>
                                <th class="d-none d-print-block">Подпись</th>
                                <th width="40" class="d-print-none"></th>
                            </tr>
                            </thead>
                            <tbody>
                            <tr v-for="(employeeSalary, index) in employeeSalaries.data">
                                <td :data-id="employeeSalary.id">{{ ((employeeSalaries.current_page - 1) * employeeSalaries.per_page) + index + 1 }}</td>
                                <td>{{employeeSalary.created_at}}</td>
                                <td>{{employeeSalary.amount}} сом.</td>
                                <td>{{employeeSalary.comment}}</td>
                                <td class="d-none d-print-block">&nbsp;</td>
                                <td class="text-center d-print-none">
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
import Filters from "./Filters.vue";
import BackBtn from "../../Shared/BackBtn.vue";

export default {
    components: {BackBtn, Filters, DeleteBtn, Pagination, Head, Link},
    props: ['employeeSalaries', 'employeeId', 'filterParams'],
}
</script>
