<template>
    <Head>
        <title>Скидки</title>
    </Head>

    <div class="content-header">
        <div class="container">
            <h5 class="m-0">Скидки</h5>
        </div>
    </div>

    <div class="content">
        <div class="container">
            <div class="card">
                <div class="card-header">
                    <div class="card-tools">
                        <Link :href="route('client-discounts.create', client.id)" class="btn btn-success btn-sm px-3">
                            Новая скидка
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
                                <th>Номенклатура</th>
                                <th>Сумма скидки</th>
                                <th width="40"></th>
                            </tr>
                            </thead>
                            <tbody>
                            <tr v-for="(clientDiscount, index) in clientDiscounts.data">
                                <td :data-id="clientDiscount.id">{{ ((clientDiscounts.current_page - 1) * clientDiscounts.per_page) + index + 1 }}</td>
                                <td>{{clientDiscount.nomenclature}}</td>
                                <td>{{numberFormat(clientDiscount.discount)}} сом.</td>
                                <td class="text-center">
                                    <Link :href="route('client-discounts.edit', {client: client.id, client_discount: clientDiscount.id})">
                                        <i class="fa fa-pencil-alt"></i>
                                    </Link>
                                </td>
                            </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
                <!-- /.card-body -->

                <div class="card-footer clearfix" v-if="clientDiscounts.links.length > 3">
                    <pagination :links="clientDiscounts.links"/>
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
    props: ['client', 'clientDiscounts'],
}
</script>
