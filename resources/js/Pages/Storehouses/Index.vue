<template>
    <Head>
        <title>Склады</title>
    </Head>

    <div class="content-header">
        <div class="container">
            <h1 class="m-0">Склады</h1>
        </div>
    </div>

    <div class="content">
        <div class="container">
            <div class="card">
                <div class="card-header">
                    <div class="card-tools">
                        <Link :href="route('storehouses.create')" class="btn btn-success btn-sm px-3">
                            Новый склад
                        </Link>
                    </div>
                </div>
                <!-- /.card-header -->
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-bordered">
                            <thead>
                            <tr>
                                <th style="width: 10px">#</th>
                                <th>Название</th>
                                <th width="40"></th>
                            </tr>
                            </thead>
                            <tbody>
                            <tr v-for="(storehouse, index) in storehouses.data">
                                <td>{{ ((storehouses.current_page - 1) * storehouses.per_page) + index + 1 }}</td>
                                <td>{{storehouse.name}}</td>
                                <td class="text-center">
                                    <Link :href="route('storehouses.edit', storehouse.id)">
                                        <i class="fa fa-pencil-alt"></i>
                                    </Link>
                                </td>
                            </tr>

                            </tbody>
                        </table>
                    </div>
                </div>
                <!-- /.card-body -->

                <div class="card-footer clearfix" v-if="storehouses.links.length > 3">
                    <pagination :links="storehouses.links"/>
                </div>
            </div>
        </div>
    </div>
</template>
<script>
import {Head, Link} from "@inertiajs/inertia-vue3";
import Pagination from "../../Shared/Pagination.vue";

export default {
    components: {Pagination, Head, Link},
    props: ['storehouses'],
}
</script>
