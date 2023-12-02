<template>
    <Head>
        <title>Сотрудники</title>
    </Head>

    <div class="content-header">
        <div class="container-fluid">
            <h1 class="m-0">Сотрудники</h1>
        </div>
    </div>

    <div class="content">
        <div class="container-fluid">
            <div class="card">
                <div class="card-header">
                    <div class="card-tools">
                        <Link :href="route('employees.create')" class="btn btn-success btn-sm px-3">
                            Новый сотрудник
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
                                <th>Имя</th>
                                <th>Дата добавления</th>
                                <th width="40"></th>
                            </tr>
                            </thead>
                            <tbody>
                            <tr v-for="(employee, index) in employees.data">
                                <td :data-id="employee.id">{{ ((employees.current_page - 1) * employees.per_page) + index + 1 }}</td>
                                <td>
                                    <Link :href="route('employees.employee-salaries.index', employee.id)">
                                        {{employee.name}}
                                    </Link>
                                </td>
                                <td>{{employee.created_at}}</td>
                                <td class="text-center">
                                    <Link class="btn btn-sm btn-outline-primary mr-2" :href="route('employees.edit', employee.id)">
                                        <i class="fa fa-pencil-alt"></i>
                                    </Link>

                                    <delete-btn
                                        :url="route('employees.destroy', employee.id)"
                                    />
                                </td>
                            </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
                <!-- /.card-body -->

                <div class="card-footer clearfix" v-if="employees.links.length > 3">
                    <pagination :links="employees.links"/>
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
    props: ['employees'],
}
</script>
