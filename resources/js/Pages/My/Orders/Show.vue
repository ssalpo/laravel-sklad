<template>
    <Head>
        <title>Заявка: #{{order.id}}</title>
    </Head>

    <div class="content-header">
        <div class="container">
            <h1 class="m-0">Заявка: #{{order.id}}</h1>
        </div>
    </div>

    <div class="content">
        <div class="container">
            <div class="card">
                <div class="card-body">
                    <h5>Состав заявки</h5>

                    <div class="table-responsive table-hover mb-4 mt-3">
                        <table width="250" border="1">
                            <tr>
                                <td class="p-1">Клиент</td>
                                <td class="p-1">{{order.client}}</td>
                            </tr>
                            <tr>
                                <td class="p-1">Статус</td>
                                <td class="p-1">{{$page.props.shared.orderStatusLabels[order.status]}}</td>
                            </tr>
                            <tr>
                                <td class="p-1">Общая сумма</td>
                                <td class="p-1">{{numberFormat(order.amount, 2)}} сом.</td>
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
                            </tr>
                            </thead>
                            <tbody>
                            <tr v-for="(orderItem, index) in orderItems">
                                <td>{{index + 1}}</td>
                                <td>{{orderItem.nomenclature}}</td>
                                <td>{{orderItem.quantity}} {{$page.props.shared.unitLabels[orderItem.unit]}}</td>
                                <td>{{numberFormat(orderItem.price_for_sale)}} сом.</td>
                                <td>{{numberFormat(orderItem.price_for_sale * orderItem.quantity)}} сом.</td>
                            </tr>
                            </tbody>
                        </table>
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

export default {
    methods: {numberFormat},
    components: {Head, Link},
    props: ['order', 'orderItems'],
}
</script>
