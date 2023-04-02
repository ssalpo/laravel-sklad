<template>
    <Head>
        <title>Долги клиентов</title>
    </Head>

    <div class="content-header">
        <div class="container">
            <h1 class="m-0">Долги клиентов</h1>
        </div>
    </div>

    <div class="content">
        <div class="container">
            <div class="card">
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-bordered  text-nowrap">
                            <thead>
                            <tr>
                                <th>Клиент</th>
                                <th>Номер заявки</th>
                                <th>Сумма долга</th>
                                <th>Остаток долга</th>
                                <th>Статус оплаты</th>
                                <th>Комментарий</th>
                                <th width="40">Действия</th>
                            </tr>
                            </thead>
                            <tbody>
                            <tr v-for="debt in debts">
                                <td>{{ debt.client }}</td>
                                <td>
                                    <Link :href="route('orders.show', debt.order_id)">Заявка №{{ debt.order_id }}</Link>
                                </td>
                                <td>{{ numberFormat(debt.amount) }} сом.</td>
                                <td>{{ numberFormat(debt.amount - debt.payments_sum_amount) }} сом.</td>
                                <td :class="[debt.amount === debt.payments_sum_amount ? 'text-success' : 'text-danger']">
                                    {{ debt.amount === debt.payments_sum_amount ? 'Оплачено' : 'Не оплачено' }}
                                </td>
                                <td>{{ debt.comment }}</td>
                                <td>
                                    <div v-if="debt.amount !== debt.payments_sum_amount">
                                        <button v-if="currentPayIndex !== debt.id" @click="currentPayIndex = debt.id"
                                                class="btn btn-sm btn-success">Погасить долг
                                        </button>
                                        <client-debtn-payment-add
                                            :submit-url="route('my.client-debt-payment.store', debt.id)"
                                            :max-amount="debt.amount - debt.payments_sum_amount"
                                            @cancel="currentPayIndex = null"
                                            @success="currentPayIndex = null"
                                            v-if="currentPayIndex === debt.id"/>
                                    </div>
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
import ClientDebtnPaymentAdd from "../../../Shared/ClientDebtnPaymentAdd.vue";

export default {
    components: {ClientDebtnPaymentAdd, Head, Link},
    props: ['debts'],
    data: () => ({
        currentPayIndex: null
    })
}
</script>
