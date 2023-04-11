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
                    <filters :filter-params="filterParams" />

                    <div class="row">
                        <div class="col-lg-4 col-sm-4 col-12">

                            <div class="small-box bg-info">
                                <div class="inner">
                                    <h3>{{ numberFormat(currentMontTotalAmounts.debit) }} с.</h3>
                                    <p>Общий приход</p>
                                </div>
                                <div class="icon">
                                    <i class="fa fa-chart-bar"></i>
                                </div>
                            </div>
                        </div>

                        <div class="col-lg-4 col-sm-4 col-12">
                            <div class="small-box bg-danger">
                                <div class="inner">
                                    <h3>{{ numberFormat(currentMontTotalAmounts.credit) }} с.</h3>
                                    <p>Уход</p>
                                </div>
                                <div class="icon">
                                    <i class="fa fa-chart-line"></i>
                                </div>
                            </div>
                        </div>

                        <div class="col-lg-4 col-sm-4 col-12">
                            <div class="small-box bg-success">
                                <div class="inner">
                                    <h3>{{ numberFormat(lastMonthDebitAmount + (currentMontTotalAmounts.debit - currentMontTotalAmounts.credit)) }} с.</h3>
                                    <p>Общий остаток</p>
                                </div>
                                <div class="icon">
                                    <i class="fa fa-chart-line"></i>
                                </div>
                            </div>
                        </div>

                    </div>

                    <div class="table-responsive">
                        <table class="table table-sm table-hover text-nowrap">
                            <thead>
                            <tr>
                                <th>Дата</th>
                                <th>Приход</th>
                                <th>Уход</th>
                                <th>Комментарий приход</th>
                                <th>Комментарий уход</th>
                            </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td colspan="3" class="text-right text-info"></td>
                                    <td colspan="2" class="text-info">
                                        Остаток в начале месяца: {{numberFormat(lastMonthDebitAmount)}} сом.
                                    </td>
                                </tr>
                                <template v-for="(transaction, date) in transactions">
                                    <tr v-for="item in transaction.items">
                                        <td>{{date}}</td>
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
                                        <td>{{item.debit_comment}}</td>
                                        <td>{{item.credit_comment}}</td>
                                    </tr>
                                    <tr class="table-active">
                                        <td></td>
                                        <td class="text-success">
                                            Итого приход: {{numberFormat(transaction.debit_amount)}} сом.
                                        </td>
                                        <td class="text-danger">
                                            Итого расход: {{numberFormat(transaction.credit_amount)}} сом.
                                        </td>
                                        <td class="text-info" colspan="2">
                                            Остаток: {{numberFormat(transaction.amount)}} сом.
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
import Filters from "./DayFilters.vue";

export default {
    components: {Filters, CashTransactionModal, Pagination, Head, Link},
    props: ['filterParams', 'currentMontTotalAmounts', 'lastMonthDebitAmount', 'transactions']
}
</script>
