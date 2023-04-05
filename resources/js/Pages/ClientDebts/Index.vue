<template>
    <Head>
        <title>Долги клиента: {{client.name}}</title>
    </Head>

    <div class="content-header">
        <div class="container">
            <h1 class="m-0">Долги клиента: {{client.name}}</h1>
        </div>
    </div>

    <div class="content">
        <div class="container">
            <div class="row">
                <div class="col-lg-4 col-sm-6 col-12">
                    <div class="small-box bg-info">
                        <div class="inner">
                            <h3>{{ numberFormat(totalDebts) }} с.</h3>
                            <p>Общая сумма долга</p>
                        </div>
                        <div class="icon">
                            <i class="fa fa-chart-bar"></i>
                        </div>
                    </div>
                </div>
                <div class="col-lg-4 col-sm-6 col-12">
                    <div class="small-box bg-info">
                        <div class="inner">
                            <h3>{{ numberFormat(totalPayments) }} с.</h3>
                            <p>Общая сумма долга</p>
                        </div>
                        <div class="icon">
                            <i class="fa fa-chart-bar"></i>
                        </div>
                    </div>
                </div>

                <div class="col-lg-4 col-sm-6 col-12">
                    <div class="small-box bg-success">
                        <div class="inner">
                            <h3>{{ numberFormat(totalDebts - totalPayments) }} с.</h3>
                            <p>Остаток для погашения</p>
                        </div>
                        <div class="icon">
                            <i class="fa fa-chart-line"></i>
                        </div>
                    </div>
                </div>
            </div>

            <div class="card">
                <div class="card-header">
                    <div class="card-tools">
                        <Link :href="route('client.debts.create', client.id)" class="btn btn-success btn-sm px-3">
                            Новый долг
                        </Link>
                    </div>
                </div>
                <!-- /.card-header -->

                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-bordered  text-nowrap">
                            <thead>
                            <tr>
                                <th>Номер заявки</th>
                                <th>Сумма погашений</th>
                                <th>Остаток</th>
                                <th>Комментарий</th>
                                <th>Статус</th>
                                <th width="40" colspan="3">Действия</th>
                            </tr>
                            </thead>
                            <tbody>
                            <tr v-for="debt in debts">
                                <td>
                                    <Link :href="route('orders.show', debt.order_id)">Заявка №{{debt.order_id}}</Link>
                                </td>
                                <td>{{numberFormat(debt.payments_sum_amount)}} сом.</td>
                                <td>{{numberFormat(debt.amount - debt.payments_sum_amount)}} сом.</td>
                                <td>{{debt.comment}}</td>
                                <td :class="[debt.is_paid ? 'text-success' : 'text-danger']">{{debt.is_paid ? 'Оплачено' : 'Не оплачено'}}</td>
                                <td width="75">
                                    <Link :href="route('client.debts.payments.index', {client: client.id, debt: debt.id})" class="btn btn-sm btn-outline-primary mr-1">История погашений</Link>
                                </td>
                                <td width="75" class="text-center">
                                    <div v-if="!debt.is_paid">
                                        <button v-if="currentPayIndex !== debt.id" @click="currentPayIndex = debt.id"
                                                class="btn btn-sm btn-primary">Погасить долг
                                        </button>

                                        <client-debt-payment-add
                                            :submit-url="route('client.debts.payments.store', {client: client.id, debt: debt.id})"
                                            :max-amount="debt.amount - debt.payments_sum_amount"
                                            @cancel="currentPayIndex = null"
                                            @success="currentPayIndex = null"
                                            v-if="currentPayIndex === debt.id"
                                        />
                                    </div>
                                    <div v-else style="width: 105px">-</div>
                                </td>
                                <td width="40">
                                    <Link class="btn btn-sm btn-outline-primary mr-1" :href="route('client.debts.edit', {client: client.id, debt: debt.id})">
                                        <i class="fa fa-pencil-alt"></i>
                                    </Link>

                                    <Link method="delete" as="button"
                                          type="button"
                                          preserve-sscroll
                                          preserve-state
                                          :href="route('client.debts.destroy', {client: client.id, debt: debt.id})"
                                          class="btn btn-sm btn-outline-danger">
                                        <i class="fa fa-trash-alt"></i>
                                    </Link>
                                </td>
                            </tr>
                            </tbody>
                        </table>
                    </div>
                </div>

            </div>
        </div>
    </div>
</template>
<script>
import {Head, Link} from "@inertiajs/inertia-vue3";
import ClientDebtPaymentAdd from "../../Shared/ClientDebtPaymentAdd.vue";

export default {
    components: {ClientDebtPaymentAdd, Head, Link},
    props: ['client', 'debts', 'totalDebts', 'totalPayments'],
    data: () => ({
        currentPayIndex: null
    })
}
</script>
