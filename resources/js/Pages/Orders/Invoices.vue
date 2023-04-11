<template>
    <Head>
        <title>Накладная</title>
    </Head>

    <div class="p-5 d-print-none">
        <Link :href="route('orders.index')">
            <i class="fa fa-long-arrow-alt-left"></i> Вернуться к списку заказов
        </Link>
    </div>

    <div class="print-date-select d-flex justify-content-center">
        <div class="col-12 col-sm-4">
            <VueDatePicker
                placeholder="Выберите дату печати"
                class="date-picker-sm"
                cancel-text="Отменить"
                select-text="Выбрать"
                v-model="printDate"
                locale="ru-RU"
                format="dd.MM.yyyy"
                :enable-time-picker="false"
            />
        </div>
    </div>

    <div class="container-fluid mt-4">
        <order-invoice
            style="page-break-inside: avoid"
            :class="{'mt-4': index > 0}"
            v-for="(order, index) in orders"
            :order="order"
            :print-date="printDate"
            :order-items="order.orderItems"
        />
    </div>
</template>
<script>
import {Head, Link} from "@inertiajs/inertia-vue3";
import AuthLayout from "../../Layouts/AuthLayout.vue";
import OrderInvoice from "../../Shared/OrderInvoice.vue";
import VueDatePicker from '@vuepic/vue-datepicker';
import '@vuepic/vue-datepicker/dist/main.css'

export default {
    components: {OrderInvoice, Head, Link, VueDatePicker},
    data: () => ({
        printDate: new Date
    }),
    props: ['orders'],
    layout: AuthLayout
}
</script>

<style>
@media print {
    .print-date-select .date-picker-sm {
        display: none;
    }
}
</style>
