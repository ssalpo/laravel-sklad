<template>
    <Head>
        <title>Мои заявки</title>
    </Head>

    <div class="content-header">
        <div class="container-fluid">
            <h5 class="m-0">Мои заявки</h5>
        </div>
    </div>

    <div class="content">
        <div class="container-fluid">
            <div class="card">
                <div class="card-body">
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
                                    <Link v-if="order.status === 1" method="delete" as="button"
                                          type="button"
                                          preserve-sscroll
                                          preserve-state
                                          :href="route('my.orders.destroy', order.id)"
                                          class="btn btn-sm btn-outline-danger">
                                        <i class="fa fa-trash-alt"></i>
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
</template>
<script>
import {Head, Link} from "@inertiajs/inertia-vue3";
import Pagination from "../../../Shared/Pagination.vue";
import OrderChangeStatusBtn from "../../../Shared/OrderChangeStatusBtn.vue";

export default {
    components: {OrderChangeStatusBtn, Pagination, Head, Link},
    props: ['orders'],
}
</script>
