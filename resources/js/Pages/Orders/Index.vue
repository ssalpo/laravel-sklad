<template>
    <Head>
        <title>Заявки</title>
    </Head>

    <div class="content-header">
        <div class="container-fluid">
            <h5 class="m-0">Заявки</h5>
        </div>
    </div>

    <div class="content">
        <div class="container-fluid">
            <div class="card">
                <div class="card-header">
                    <button @click="isFilterShow = !isFilterShow" class="btn btn-sm btn-outline-info d-inline-block d-sm-none">
                        <span class="fa fa-filter"></span>
                    </button>

                    <div class="card-tools">
                        <Link :href="route('orders.create')" class="btn btn-success btn-sm px-3">
                            Новая заявка
                        </Link>
                    </div>
                </div>
                <!-- /.card-header -->

                <div class="card-body">
                    <div class="table-responsive">

                        <filters
                            v-show="isFilterShow"
                            class="d-sm-block"
                            :filter-params="filterParams"
                        />

                        <table class="table table-bordered  text-nowrap">
                            <thead>
                            <tr>
                                <th style="width: 10px">#</th>
                                <th>Заявщик</th>
                                <th>Клиент</th>
                                <th>Сумма</th>
                                <th colspan="2">Статус</th>
                                <th>Прибыль</th>
                                <th width="100">Остаток долга</th>
                                <th>Дата отправки</th>
                                <th>Дата создания</th>
                                <th></th>
                            </tr>
                            </thead>
                            <tbody>
                            <tr v-for="order in orders.data" :class="{'table-warning': !order.has_cash_transaction}">
                                <td>{{ order.id }}</td>
                                <td>
                                    <Link :href="route('orders.show', order.id)">{{ order.user }}</Link>
                                </td>
                                <td>{{ order.client.name }}</td>
                                <td>{{ numberFormat(order.amount) }} сом.</td>
                                <td>
                                    {{$page.props.shared.orderStatusLabels[order.status]}}
                                </td>
                                <td>
                                    <order-change-status-btn
                                        rollback-btn
                                        size="btn-xs"
                                        :order-id="order.id"
                                        :status="order.status"
                                    />
                                </td>
                                <td>{{ numberFormat(order.profit) }} сом.</td>
                                <td>{{ orderDebtAmounts[order.id] !== undefined ? `${numberFormat(orderDebtAmounts[order.id])} сом.` : '-' }}</td>
                                <td>{{ order.send_at }}</td>
                                <td>{{ order.created_at }}</td>
                                <td width="100">
                                    <button :class="[!this.invoices.includes(order.id) ? 'btn-outline-primary' : 'btn-outline-success']" class="btn btn-sm mr-1" @click="toggleInvoice(order.id)">Накладная</button>
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

    <Teleport to="body" v-if="this.invoices.length">
        <div class="content-wrapper d-flex align-items-center" style="height: 60px; width: 100%; position: fixed; bottom: 0; background: #dee2e6">
            <div class="container-fluid d-flex align-items-center justify-content-end">
                <span>Выбранных заявок: {{this.invoices.length}}</span>

                <Link @click="clearInvoices" :href="route('order-invoices', {ids: this.invoices.join(',')})" class="ml-3 btn btn-sm btn-outline-success">Печать</Link>
                <button class="ml-3 btn btn-sm btn-outline-danger" @click="clearInvoices">
                    <i class="fa fa-times"></i>
                </button>
            </div>
        </div>
    </Teleport>
</template>
<script>
import {Head, Link} from "@inertiajs/inertia-vue3";
import Pagination from "@/Shared/Pagination.vue";
import OrderChangeStatusBtn from "@/Shared/OrderChangeStatusBtn.vue";
import Filters from "./Filters.vue";
import size from "lodash/size";
import {numberFormat} from "../../functions";
export default {
    components: {Filters, OrderChangeStatusBtn, Pagination, Head, Link},
    props: ['orders', 'filterParams', 'orderDebtAmounts'],
    data() {
        return {
            isFilterShow: false,
            invoices: []
        }
    },
    created() {
        if (this.$cookies.isKey('invoices')) {
            this.invoices = this.$cookies.get('invoices');
        }

        this.isFilterShow = size(this.filterParams);
    },
    methods: {
        numberFormat,
        toggleInvoice(id) {
            let index = this.invoices.indexOf(id);

            if(index > -1) {
                this.invoices.splice(index, 1)
            } else {
                this.invoices.push(id)
            }

            this.$cookies.set('invoices', JSON.stringify(this.invoices));
        },
        clearInvoices() {
            this.invoices = [];
            this.$cookies.remove('invoices');
        }
    }
}
</script>
