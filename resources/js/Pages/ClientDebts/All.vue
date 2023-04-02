<template>
    <Head>
        <title>Долги клиентов</title>
    </Head>

    <div class="content-header">
        <div class="container">
            <h1 class="m-0">Долги клиентов</h1>
        </div>
    </div>

    <div class="content">
        <div class="container">
            <div class="row">
                <div class="col-lg-4 col-sm-6 col-12">

                    <div class="small-box bg-info">
                        <div class="inner">
                            <h3>{{ numberFormat(totalDebts) }} с.</h3>
                            <p>Общая сумма долга</p>
                        </div>
                        <div class="icon">
                            <i class="fa fa-chart-bar"></i>
                        </div>
                    </div>
                </div>
            </div>

            <div class="card">
                <div class="card-header">
                    <div class="row">
                        <div class="col-8 col-sm-3">
                            <select v-model="filters.client" class="form-control form-control-sm">
                                <option :value="null">Выбрать клиента</option>
                                <option :value="client.id" v-for="client in clients">{{ client.name }}</option>
                            </select>
                        </div>
                        <div class="col-4 col-sm-6">
                            <button @click="search" class="btn btn-sm btn-primary mr-1">
                                <span class="fa fa-search"></span>
                            </button>
                            <button @click="reset" v-if="selectedFilter.client" class="btn btn-sm btn-danger">
                                <span class="fa fa-times"></span>
                            </button>
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
                                <th>Имя</th>
                                <th>Общий долг</th>
                            </tr>
                            </thead>
                            <tbody>
                            <tr v-for="(debt, index) in debts">
                                <td>{{ index + 1 }}</td>
                                <td>
                                    <Link :href="route('client.debts.index', debt.client_id)">{{ debt.client_name }}
                                    </Link>
                                </td>
                                <td>{{ numberFormat(debt.totalAmount) }} сом.</td>
                            </tr>
                            </tbody>
                        </table>
                    </div>
                </div>

            </div>
        </div>
    </div>
</template>
<script>
import {Head, Link, useForm} from "@inertiajs/inertia-vue3";

export default {
    components: {Head, Link},
    props: ['debts', 'totalDebts', 'clients', 'selectedFilter'],
    data() {
        return {
            filters: useForm({
                client: this.selectedFilter?.client || null
            })
        }
    },
    methods: {
        search() {
            this.filters.get(route('all-client-debts'))
        },
        reset() {
            this.filters.client = '';

            this.$inertia.visit(route('all-client-debts'));
        }
    }
}
</script>
