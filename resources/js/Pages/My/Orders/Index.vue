<template>
    <Head>
        <title>Мои заявки</title>
    </Head>

    <div class="content-header">
        <div class="container-fluid">
            <h5 class="m-0">Мои заявки</h5>
        </div>
    </div>

    <div class="card">
        <div class="card-header text-right d-block d-sm-none">
            <button @click="isFilterShow = !isFilterShow" class="btn btn-sm btn-outline-info d-inline-block d-sm-none">
                <span class="fa fa-filter"></span> Фильтр
            </button>
        </div>
        <!-- /.card-header -->

        <div class="card-body">
            <filters
                v-show="isFilterShow"
                class="d-sm-block"
                :filter-params="filterParams"
            />

            <div class="table-responsive">
                <table class="table table-bordered  text-nowrap">
                    <thead>
                    <tr>
                        <th style="width: 10px">#</th>
                        <th>Клиент</th>
                        <th>Сумма</th>
                        <th colspan="2">Статус</th>
                        <th width="100">Долги</th>
                        <th>Дата создания</th>
                        <th width="100">Действия</th>
                    </tr>
                    </thead>
                    <tbody>
                    <tr v-for="order in orders.data">
                        <td>{{order.id}}</td>
                        <td><Link :href="route('my.orders.show', order.id)">{{order.client_name}}</Link></td>
                        <td>{{order.amount}} сом.</td>
                        <td>{{$page.props.shared.orderStatusLabels[order.status]}}</td>
                        <td class="text-center">
                            <order-change-status-btn
                                size="btn-xs"
                                for-profile
                                :order-id="order.id"
                                :status="order.status"
                            />
                        </td>
                        <td>
                            <Link class="btn btn-xs btn-outline-success mr-2" :href="route('my.client-debts.index', {client: order.client_id, order: order.id})">
                                История долгов
                            </Link>

                            <Link class="btn btn-xs btn-outline-primary mr-2" :href="route('my.order-debts.create', order.id)">
                                Добавить долг
                            </Link>
                        </td>
                        <td>{{order.created_at}}</td>
                        <td class="text-center">
                            <delete-btn
                                v-if="order.status === 1"
                                :url="route('my.orders.destroy', order.id)"
                            />
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
</template>
<script>
import {Head, Link} from "@inertiajs/inertia-vue3";
import Pagination from "@/Shared/Pagination.vue";
import OrderChangeStatusBtn from "@/Shared/OrderChangeStatusBtn.vue";
import Filters from "./Filters.vue";
import size from "lodash/size";
import DeleteBtn from "@/Shared/DeleteBtn.vue";

export default {
    components: {DeleteBtn, Filters, OrderChangeStatusBtn, Pagination, Head, Link},
    props: ['orders', 'filterParams'],
    data: () => ({
        isFilterShow: true
    }),
    created() {
        this.isFilterShow = size(this.filterParams);
    }
}
</script>
