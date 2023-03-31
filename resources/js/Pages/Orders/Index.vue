<template>
    <Head>
        <title>Заявки</title>
    </Head>

    <div class="content-header">
        <div class="container">
            <h5 class="m-0">Заявки</h5>
        </div>
    </div>

    <div class="content">
        <div class="container">
            <div class="card">
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-bordered  text-nowrap">
                            <thead>
                            <tr>
                                <th style="width: 10px">#</th>
                                <th>Заявщик</th>
                                <th>Клиент</th>
                                <th>Сумма</th>
                                <th width="130">Статус</th>
                                <th>Прибыль</th>
                                <th></th>
                            </tr>
                            </thead>
                            <tbody>
                            <tr v-for="order in orders.data">
                                <td>{{ order.id }}</td>
                                <td>
                                    <Link :href="route('orders.show', order.id)">{{ order.user }}</Link>
                                </td>
                                <td>{{ order.client }}</td>
                                <td>{{ numberFormat(order.amount) }} сом.</td>
                                <td>
                                    <order-status-toggle style="width: 105px" :order-status="order.status" :order-id="order.id" />
                                </td>
                                <td>{{ numberFormat(order.profit) }} сом.</td>
                                <td>
                                    <Link :href="route('orders.invoice', order.id)">Накладная</Link>
                                </td>
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
import OrderStatusToggle from "../../Shared/OrderStatusToggle.vue";

export default {
    components: {OrderStatusToggle, Pagination, Head, Link},
    props: ['orders']
}
</script>
