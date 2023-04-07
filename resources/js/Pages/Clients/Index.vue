<template>
    <Head>
        <title>Клиенты</title>
    </Head>

    <div class="content-header">
        <div class="container-fluid">
            <h1 class="m-0">Клиенты</h1>
        </div>
    </div>

    <div class="content">
        <div class="container-fluid">
            <div class="card">
                <div class="card-header">
                    <div class="card-tools">
                        <Link :href="route('clients.create')" class="btn btn-success btn-sm px-3">
                            Новый клиент
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
                                <th>Наименование</th>
                                <th>Телефон</th>
                                <th>Дата создания</th>
                                <th></th>
                                <th width="40"></th>
                            </tr>
                            </thead>
                            <tbody>
                            <tr v-for="(client, index) in clients.data">
                                <td :data-id="client.id">{{ ((clients.current_page - 1) * clients.per_page) + index + 1 }}</td>
                                <td>{{client.name}}</td>
                                <td>{{client.phone}}</td>
                                <td>{{client.created_at}}</td>
                                <td>
                                    <Link :href="route('client-discounts.index', client.id)">Скидки</Link>
                                </td>
                                <td class="text-center">
                                    <Link :href="route('clients.edit', client.id)">
                                        <i class="fa fa-pencil-alt"></i>
                                    </Link>
                                </td>
                            </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
                <!-- /.card-body -->

                <div class="card-footer clearfix" v-if="clients.links.length > 3">
                    <pagination :links="clients.links"/>
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
    props: ['clients'],
}
</script>
