<template>
    <Head>
        <title>Накладная</title>
    </Head>

    <div class="p-5 d-print-none">
        <Link :href="route('orders.index')">
            <i class="fa fa-long-arrow-alt-left"></i> Вернуться к списку заказов
        </Link>
    </div>

    <div class="container-fluid mt-4">
        <order-invoice
            style="page-break-inside: avoid"
            :class="{'mt-4': index > 0}"
            v-for="(order, index) in orders"
            :order="order"
            :order-items="order.orderItems"
        />
    </div>
</template>
<script>
import {Head, Link} from "@inertiajs/inertia-vue3";
import AuthLayout from "../../Layouts/AuthLayout.vue";
import OrderInvoice from "../../Shared/OrderInvoice.vue";

export default {
    components: {OrderInvoice, Head, Link},
    props: ['orders'],
    layout: AuthLayout
}
</script>
