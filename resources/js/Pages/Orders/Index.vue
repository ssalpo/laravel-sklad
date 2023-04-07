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
                <div class="card-header">
                    <div class="card-tools">
                        <Link :href="route('orders.create')" class="btn btn-success btn-sm px-3">
                            Новая заявка
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
                                <th>Заявщик</th>
                                <th>Клиент</th>
                                <th>Сумма</th>
                                <th width="130">Статус</th>
                                <th>Прибыль</th>
                                <th>Дата создания</th>
                                <th></th>
                            </tr>
                            </thead>
                            <tbody>
                            <tr v-for="order in orders.data">
                                <td>{{ order.id }}</td>
                                <td>
                                    <Link :href="route('orders.show', order.id)">{{ order.user }}</Link>
                                </td>
                                <td>{{ order.client.name }}</td>
                                <td>{{ numberFormat(order.amount) }} сом.</td>
                                <td>
                                    <order-status-toggle style="width: 120px" :order-status="order.status"
                                                         :order-id="order.id"/>
                                </td>
                                <td>{{ numberFormat(order.profit) }} сом.</td>
                                <td>{{ order.created_at }}</td>
                                <td width="150">
                                    <button :class="[!this.invoices.includes(order.id) ? 'btn-outline-primary' : 'btn-outline-success']" class="btn btn-sm mr-1" @click="toggleInvoice(order.id)">Накладная</button>

                                    <Link
                                        :href="route('client.debts.create', {client: order.client.id, order: order.id})"
                                        class="btn btn-sm btn-outline-primary mr-1">Добавить долг
                                    </Link>
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
            <div class="container d-flex align-items-center justify-content-end">
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
import Pagination from "../../Shared/Pagination.vue";
import OrderStatusToggle from "../../Shared/OrderStatusToggle.vue";

export default {
    components: {OrderStatusToggle, Pagination, Head, Link},
    props: ['orders'],
    data() {
        return {
            invoices: []
        }
    },
    created() {
        if (this.$cookies.isKey('invoices')) {
            this.invoices = this.$cookies.get('invoices');
        }
    },
    methods: {
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
