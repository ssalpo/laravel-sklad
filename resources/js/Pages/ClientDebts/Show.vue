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
            <div class="card">
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-bordered  text-nowrap">
                            <thead>
                            <tr>
                                <th>Номер заявки</th>
                                <th>Сумма долга</th>
                                <th>Комментарий</th>
                                <th>Статус оплаты</th>
                                <th width="40">Действия</th>
                            </tr>
                            </thead>
                            <tbody>
                            <tr v-for="debt in debts">
                                <td>
                                    <Link :href="route('orders.show', debt.order_id)">Заявка №{{debt.order_id}}</Link>
                                </td>
                                <td>{{numberFormat(debt.amount)}} сом.</td>
                                <td>{{debt.is_paid ? 'Оплачено' : 'Не оплачено'}}</td>
                                <td>{{debt.comment}}</td>
                                <td>
                                    <Link v-if="!debt.is_paid" method="post" as="button"
                                          type="button"
                                          preserve-sscroll
                                          preserve-state
                                          :href="route('client-debts.mark-as-paid', {client: client.id, client_debt: debt.id})"
                                          class="btn btn-sm btn-primary">
                                        Оплатить
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

export default {
    components: {Head, Link},
    props: ['client', 'debts'],
}
</script>
