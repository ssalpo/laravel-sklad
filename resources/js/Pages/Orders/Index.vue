<template>
    <Head>
        <title>Заявки</title>
    </Head>

    <div class="content-header">
        <div class="container">
            <h1 class="m-0">Заявки</h1>
        </div>
    </div>

    <div class="content">
        <div class="container">
            <div class="card">
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-bordered">
                            <thead>
                            <tr>
                                <th style="width: 10px">#</th>
                                <th>Заявщик</th>
                                <th>Клиент</th>
                                <th>Сумма</th>
                                <th>Статус</th>
                                <th>Прибыль</th>
                            </tr>
                            </thead>
                            <tbody>
                            <tr v-for="order in orders.data">
                                <td>{{order.id}}</td>
                                <td><Link :href="route('orders.show', order.id)">{{order.user}}</Link></td>
                                <td>{{order.client}}</td>
                                <td>{{order.amount}} {{$page.props.shared.currencyTypeLabelsShort[order.currency_type]}}</td>
                                <td>{{$page.props.shared.orderStatusLabels[order.status]}}</td>
                                <td>-</td>
                            </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
                <!-- /.card-body -->

                <div class="card-footer clearfix" v-if="orders.links.length > 3">
                    <pagination :links="orders.links"/>
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
    props: ['orders'],
}
</script>
