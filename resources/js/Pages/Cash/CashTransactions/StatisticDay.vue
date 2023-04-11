<template>
    <Head>
        <title>ДДС день</title>
    </Head>

    <div class="content-header">
        <div class="container-fluid">
            <h1 class="m-0">ДДС день</h1>
        </div>
    </div>

    <div class="content">
        <div class="container-fluid">
            <div class="card">
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-sm table-hover">
                            <thead>
                            <tr>
                                <th>Дата</th>
                                <th>Комментарий приход</th>
                                <th>Приход</th>
                                <th>Уход</th>
                                <th>Комментарий уход</th>
                            </tr>
                            </thead>
                            <tbody>
                                <template v-for="(transaction, date) in transactions">
                                    <tr v-for="item in transaction.items">
                                        <td>{{date}}</td>
                                        <td>{{item.debit_comment}}</td>
                                        <td>
                                            <span v-show="item.debit">
                                                {{numberFormat(item.debit)}} сом.
                                            </span>
                                            <span v-show="!item.debit">-</span>
                                        </td>
                                        <td>
                                            <span v-show="item.credit">
                                                {{numberFormat(item.credit)}} сом.
                                            </span>
                                            <span v-show="!item.credit">-</span>
                                        </td>
                                        <td>{{item.credit_comment}}</td>
                                    </tr>
                                    <tr class="table-active">
                                        <td colspan="5">
                                            Итого: {{numberFormat(transaction.amount)}} сом.
                                        </td>
                                    </tr>
                                </template>
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
import Pagination from "@/Shared/Pagination.vue";
import CashTransactionModal from "@/Shared/CashTransactionModal.vue";
import get from "lodash/get";
import {numberFormat} from "@/functions";

export default {
    components: {CashTransactionModal, Pagination, Head, Link},
    props: ['transactions']
}
</script>
