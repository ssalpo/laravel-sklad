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
                    <div class="row align-items-center">
                        <div class="col-12 col-sm-6 col-md-4 mb-2 mb-sm-0">
                            <form @submit.prevent="search" class="d-flex">
                                <input
                                    v-model="filters.name"
                                    type="text"
                                    class="form-control form-control-sm mr-1"
                                    placeholder="Имя клиента"
                                />
                                <button class="btn btn-sm btn-primary mr-1" type="submit">
                                    <span class="fa fa-search"></span>
                                </button>
                                <button v-if="isFiltered" type="button" class="btn btn-sm btn-danger" @click="reset">
                                    <span class="fa fa-times"></span>
                                </button>
                            </form>
                        </div>
                        <div class="col-12 col-sm-6 col-md-8">
                            <div class="card-tools">
                                <Link :href="route('clients.create')" class="btn btn-success btn-sm px-3">
                                    Новый клиент
                                </Link>
                            </div>
                        </div>
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
                                <th>Скидки</th>
                                <th>Цены</th>
                                <th width="40"></th>
                            </tr>
                            </thead>
                            <tbody>
                            <tr v-for="(client, index) in clients.data" :key="client.id">
                                <td :data-id="client.id">{{ ((clients.current_page - 1) * clients.per_page) + index + 1 }}</td>
                                <td>{{client.name}}</td>
                                <td>{{client.phone}}</td>
                                <td>{{client.created_at}}</td>
                                <td>
                                    <Link :href="route('client-discounts.index', client.id)">Скидки</Link>
                                </td>
                                <td>
                                    <Link :href="route('client-price-templates.index', client.id)">Цены</Link>
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
import {Head, Link, useForm} from "@inertiajs/inertia-vue3";
import Pagination from "../../Shared/Pagination.vue";
import size from "lodash/size";

export default {
    components: {Pagination, Head, Link},
    props: ['clients', 'selectedFilter'],
    data() {
        return {
            filters: useForm({
                name: this.selectedFilter?.name,
            }),
        }
    },
    computed: {
        isFiltered() {
            return size(this.selectedFilter);
        },
    },
    methods: {
        search() {
            this.filters.get(route('clients.index'));
        },
        reset() {
            this.filters.reset();

            this.$inertia.visit(route('clients.index'));
        },
    },
}
</script>
