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
                                rollback-btn
                                :order-id="order.id"
                                :status="order.status" />
                        </div>
                    </div>

                    <div class="mt-2">
                        <Link
                            v-if="!order.has_debt"
                            :href="route('client.debts.create', {client: order.client_id, order: order.id})"
                            class="btn btn-sm btn-outline-primary mr-1">
                            Добавить долг
                        </Link>

                        <order-do-payment-btn
                            v-if="!order.has_cash_transaction && orderIsSend(order.status) && order.debt.amount < order.amount"
                            :order-id="order.id"
                            :already-has-debt="order.has_debt === true"
                        />
                    </div>

                    <div class="table-responsive mb-4 mt-3">
                        <table class="table table-bordered table-hover" style="max-width: 450px;">
                            <tr>
                                <td class="p-2">Заявщик</td>
                                <td class="p-2">{{order.user}}</td>
                            </tr>
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
                                <td class="p-2">{{numberFormat(order.amount)}} сом.</td>
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
                                <th>Себестоимость товара</th>
                                <th>Цена за единицу</th>
                                <th>Кол-во</th>
                                <th>Сумма продажи</th>
                                <th>Прибыль</th>
                                <th colspan="2">Возвраты</th>
                            </tr>
                            </thead>
                            <tbody>
                            <tr v-for="(orderItem, index) in orderItems">
                                <td :data-id="orderItem.nomenclature_id">{{index + 1}}</td>
                                <td>{{orderItem.nomenclature_name}}</td>
                                <td>{{orderItem.price}} сом.</td>
                                <td>{{numberFormat(orderItem.price_for_sale)}} сом.</td>
                                <td>{{orderItem.quantity}} {{$page.props.shared.unitLabels[orderItem.unit]}}</td>
                                <td>{{numberFormat(orderItem.price_for_sale * orderItem.quantity)}} сом.</td>
                                <td>{{numberFormat((orderItem.price_for_sale - orderItem.price) * orderItem.quantity)}} сом.</td>
                                <td class="text-center">
                                    <span v-if="orderTotalRefunds[orderItem.nomenclature_id]">
                                        {{orderTotalRefunds[orderItem.nomenclature_id]['quantity']}} шт., сумму: {{orderTotalRefunds[orderItem.nomenclature_id]['amount']}} сом.
                                    </span>
                                    <span v-else>-</span>
                                </td>
                                <td>
                                    <order-refund-modal
                                        v-if="(!order.has_debt && !order.has_cash_transaction) && orderIsSend(order.status) && (!orderTotalRefunds[orderItem.nomenclature_id] || (orderTotalRefunds[orderItem.nomenclature_id] && orderItem.quantity > orderTotalRefunds[orderItem.nomenclature_id]['quantity']))"
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
                                    <td>Действия</td>
                                </tr>
                                </thead>
                                <tbody>
                                    <tr v-for="item in orderRefunds">
                                        <td>{{item.nomenclature}}</td>
                                        <td>{{item.quantity}} {{item.nomenclature_unit}}</td>
                                        <td>{{item.price_for_sale}} сом.</td>
                                        <td>{{item.price_for_sale * item.quantity}} сом.</td>
                                        <td>{{item.comment}}</td>
                                        <td width="100" class="text-center">
                                            <delete-btn
                                                v-if="!order.has_debt && !order.has_cash_transaction"
                                                :url="route('nomenclature-operations.destroy', {nomenclature_operation: item.id})"
                                            />
                                        </td>
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
import OrderChangeStatusBtn from "@/Shared/OrderChangeStatusBtn.vue";
import OrderRefundModal from "@/Shared/OrderRefundModal.vue";
import {orderIsCancel, orderIsSend} from "@/Constants/order";
import {numberFormat} from "../../functions";
import OrderDoPaymentBtn from "@/Shared/OrderDoPaymentBtn.vue";
import DeleteBtn from "../../Shared/DeleteBtn.vue";

export default {
    methods: {numberFormat, orderIsSend, orderIsCancel},
    components: {DeleteBtn, OrderDoPaymentBtn, OrderRefundModal, OrderChangeStatusBtn, Head, Link},
    props: ['order', 'orderItems', 'orderTotalRefunds', 'orderRefunds'],
}
</script>
