<template>
    <Head>
        <title>Список погашений по заявке №{{debt.order}}</title>
    </Head>

    <div class="content-header">
        <div class="container">
            <h1 class="m-0">Список погашений по заявке №{{debt.order}}</h1>
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
                                <th>Сумма</th>
                                <th>Дата</th>
                                <th width="40"></th>
                            </tr>
                            </thead>
                            <tbody>
                            <tr v-for="payment in debt.payments">
                                <td>{{numberFormat(payment.amount)}} сом.</td>
                                <td>{{payment.created_at}}</td>
                                <td>
                                    <Link method="delete" as="button"
                                          type="button"
                                          preserve-sscroll
                                          preserve-state
                                          :href="route('client.debts.payments.destroy', {client: queryParams.client, debt: queryParams.debt, payment: payment.id})"
                                          class="btn btn-sm btn-link">
                                        <i class="fa fa-trash-alt text-danger"></i>
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
    props: ['debt', 'queryParams'],
}
</script>
