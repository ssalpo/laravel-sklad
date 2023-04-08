<template>
    <Head>
        <title>Заявка: #{{order.id}}</title>
    </Head>

    <div class="content-header">
        <div class="container-fluid">
            <h1 class="m-0">Заявка: #{{order.id}}</h1>
        </div>
    </div>

    <div class="content">
        <div class="container-fluid">
            <div class="card">
                <div class="card-body">
                    <div class="row">
                        <div class="col-8 col-sm-6">
                            <h5>Состав заявки</h5>
                        </div>
                        <div class="col-4 col-sm-6 text-right">
                            <order-change-status-btn
                                for-profile
                                :order-id="order.id"
                                :status="order.status"
                            />
                        </div>
                    </div>

                    <div class="table-responsive table-hover mb-4 mt-3">
                        <table class="table table-bordered table-hover" style="max-width: 450px;">
                            <tr>
                                <td class="p-2">Клиент</td>
                                <td class="p-2">{{order.client}}</td>
                            </tr>
                            <tr>
                                <td class="p-2">Статус</td>
                                <td class="p-2" :class="{'text-danger': orderIsCancel(order.status), 'text-success': orderIsSend(order.status)}">
                                    {{$page.props.shared.orderStatusLabels[order.status]}}
                                </td>
                            </tr>
                            <tr>
                                <td class="p-2">Общая сумма</td>
                                <td class="p-2">{{numberFormat(order.amount, 2)}} сом.</td>
                            </tr>
                        </table>
                    </div>

                    <hr />

                    <h5 class="mb-3">Товары</h5>

                    <div class="table-responsive">
                        <table class="table table-bordered  text-nowrap">
                            <thead>
                            <tr>
                                <th style="width: 10px">#</th>
                                <th>Номенклатура</th>
                                <th>Кол-во</th>
                                <th>Цена за единицу</th>
                                <th>Сумма продажи</th>
                                <th>Комментарий</th>
                                <th>Возвраты</th>
                            </tr>
                            </thead>
                            <tbody>
                            <tr v-for="(orderItem, index) in orderItems">
                                <td>{{index + 1}}</td>
                                <td>{{orderItem.nomenclature_name}}</td>
                                <td>{{orderItem.quantity}} {{$page.props.shared.unitLabels[orderItem.unit]}}</td>
                                <td>{{numberFormat(orderItem.price_for_sale)}} сом.</td>
                                <td>{{numberFormat(orderItem.price_for_sale * orderItem.quantity)}} сом.</td>
                                <td class="text-center">
                                        <span v-if="orderTotalRefunds[orderItem.nomenclature_id]">
                                            {{orderTotalRefunds[orderItem.nomenclature_id]['quantity']}} шт., сумму: {{orderTotalRefunds[orderItem.nomenclature_id]['amount']}} сом.
                                        </span>
                                    <span v-else>-</span>
                                </td>
                                <td>
                                    <order-refund-modal
                                        for-profile
                                        v-if="(!orderTotalRefunds[orderItem.nomenclature_id] || (orderTotalRefunds[orderItem.nomenclature_id] && orderItem.quantity > orderTotalRefunds[orderItem.nomenclature_id]['quantity'])) && !orderIsCancel(order.status)"
                                        :order-id="order.id"
                                        :order-item-id="orderItem.id"
                                        :nomenclature-id="orderItem.nomenclature_id"
                                        :nomenclature-quantity="orderItem.quantity"
                                    />
                                </td>
                            </tr>
                            </tbody>
                        </table>
                    </div>

                    <div v-if="orderRefunds.length">
                        <h5 class="mb-3">Возвраты номенклатуры</h5>

                        <div class="table-responsive">
                            <table class="table table-bordered  text-nowrap">
                                <thead>
                                <tr>
                                    <th>Номенклатура</th>
                                    <th>Кол-во</th>
                                    <th>Цена за единицу</th>
                                    <th>Общая сумма</th>
                                    <th>Комментарий</th>
                                </tr>
                                </thead>
                                <tbody>
                                <tr v-for="item in orderRefunds">
                                    <td>{{item.nomenclature}}</td>
                                    <td>{{item.quantity}} {{item.nomenclature_unit}}</td>
                                    <td>{{item.price_for_sale}} сом.</td>
                                    <td>{{item.price_for_sale * item.quantity}} сом.</td>
                                    <td>{{item.comment}}</td>
                                </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
                <!-- /.card-body -->

            </div>
        </div>
    </div>
</template>
<script>
import {Head, Link} from "@inertiajs/inertia-vue3";
import {numberFormat} from "../../../functions";
import OrderChangeStatusBtn from "../../../Shared/OrderChangeStatusBtn.vue";
import {orderIsCancel, orderIsSend} from "../../../Constants/order";
import OrderRefundModal from "../../../Shared/OrderRefundModal.vue";

export default {
    methods: {orderIsSend, orderIsCancel, numberFormat},
    components: {OrderRefundModal, OrderChangeStatusBtn, Head, Link},
    props: ['order', 'orderItems', 'orderTotalRefunds', 'orderRefunds']
}
</script>
