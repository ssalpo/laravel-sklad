<template>
    <Head>
        <title>Возвраты</title>
    </Head>

    <div class="content-header">
        <div class="container-fluid">
            <h5 class="m-0">Возвраты</h5>
        </div>
    </div>

    <div class="content">
        <div class="container-fluid">
            <div class="card">
                <div class="card-body">

                    <Filters
                        :filter-params="filterParams"
                    />

                    <div class="table-responsive">
                        <table class="table table-bordered text-nowrap">
                            <thead>
                            <tr>
                                <th style="width: 10px">#</th>
                                <th>Заявка</th>
                                <th>Номенклатура</th>
                                <th>Кол-во</th>
                                <th>Сумма за ед.</th>
                                <th>Общая сумма</th>
                                <th>Комментарий</th>
                                <th>Дата возврата</th>
                            </tr>
                            </thead>
                            <tbody>
                            <tr v-for="(nomenclatureRefund, index) in nomenclatureRefunds.data">
                                <td :data-id="nomenclatureRefund.id">{{nomenclatureRefund.id}}</td>
                                <td>
                                    <Link :href="route('orders.show', nomenclatureRefund.order_id)">
                                        Заявка №{{nomenclatureRefund.order_id}}
                                    </Link>
                                </td>
                                <td>{{nomenclatureRefund.nomenclature.name}}</td>
                                <td>{{nomenclatureRefund.quantity}} {{nomenclatureRefund.nomenclature.unit}}</td>
                                <td>{{nomenclatureRefund.price}} сом.</td>
                                <td>{{nomenclatureRefund.price_for_sale}} сом.</td>
                                <td>{{nomenclatureRefund.comment}}</td>
                                <td>{{nomenclatureRefund.created_at}}</td>
                            </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
                <!-- /.card-body -->

                <div class="card-footer clearfix" v-if="nomenclatureRefunds.links.length > 3">
                    <pagination :links="nomenclatureRefunds.links"/>
                </div>
            </div>
        </div>
    </div>
</template>
<script>
import {Head, Link} from "@inertiajs/inertia-vue3";
import Pagination from "@/Shared/Pagination.vue";
import Filters from "./Filters.vue";

export default {
    components: {Filters, Pagination, Head, Link},
    props: ['nomenclatureRefunds', 'filterParams'],
}
</script>
