<template>
    <Head>
        <title>Операции</title>
    </Head>

    <div class="content-header">
        <div class="container-fluid">
            <h1 class="m-0">Операции</h1>
        </div>
    </div>

    <div class="content">
        <div class="container-fluid">
            <div class="card">
                <div class="card-header">
                    <cash-transaction-modal/>
                </div>
                <!-- /.card-header -->

                <div class="card-body">
                    <filters
                        :filter-params="filterParams"
                    />

                    <div class="table-responsive">
                        <table class="table table-bordered  text-nowrap">
                            <thead>
                            <tr>
                                <th width="10">#</th>
                                <th width="100" class="text-center">Тип операции</th>
                                <th width="150" class="text-center">Сумма</th>
                                <th width="150" class="text-center">Сумма в $$$</th>
                                <th width="150" class="text-center">Статус</th>
                                <th width="150" class="text-center">Безвозвратный</th>
                                <th width="100">Дата</th>
                                <th>Комментарий</th>
                                <th width="100">Действия</th>
                            </tr>
                            </thead>
                            <tbody>
                            <tr
                                :class="{'table-danger': cashTransaction.status === 2}"
                                v-for="(cashTransaction, index) in cashTransactions.data">
                                <td>
                                    {{cashTransaction.id}}
                                </td>
                                <td class="text-center">
                                    <div class="badge"
                                         :class="{'badge-success': cashTransaction.type === 1, 'badge-danger': cashTransaction.type === 2, }">
                                        {{ cashTransaction.type_label }}
                                    </div>
                                </td>
                                <td class="text-center">{{ numberFormat(cashTransaction.amount) }} сом.</td>
                                <td class="text-center">
                                    <cash-transaction-dollar-exchange
                                        :cash-transaction="cashTransaction"
                                    />
                                </td>
                                <td class="text-center" :class="{'text-success': cashTransaction.status === 1}">{{cashTransaction.status_label}}</td>
                                <td class="text-center" :class="{'table-danger': cashTransaction.is_irrevocably}">{{ cashTransaction.is_irrevocably ? 'ДА' : 'НЕТ' }}</td>
                                <td>{{ cashTransaction.created_at }}</td>
                                <td>{{ cashTransaction.comment }}</td>
                                <td>
                                    <cash-transaction-modal
                                        v-if="!cashTransaction.order_id && !cashTransaction.nomenclature_operation_id && !cashTransaction.client_debt_id && !cashTransaction.client_debt_payment_id"
                                        btn-class="mr-2"
                                        :key="cashTransaction.id"
                                        :transaction="cashTransaction"
                                    />

                                    <delete-btn
                                        v-if="!cashTransaction.order_id && !cashTransaction.nomenclature_operation_id && !cashTransaction.client_debt_id && !cashTransaction.client_debt_payment_id"
                                        :url="route('cash-transactions.destroy', cashTransaction.id)"
                                    />
                                </td>
                            </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
                <!-- /.card-body -->

                <div class="card-footer clearfix" v-if="cashTransactions.links.length > 3">
                    <pagination :links="cashTransactions.links"/>
                </div>
            </div>
        </div>
    </div>
</template>
<script>
import {Head, Link} from "@inertiajs/inertia-vue3";
import Pagination from "@/Shared/Pagination.vue";
import CashTransactionModal from "@/Shared/CashTransactionModal.vue";
import Filters from "./Filters.vue";
import DeleteBtn from "../../../Shared/DeleteBtn.vue";
import CashTransactionDollarExchange from "../../../Shared/CashTransactionDollarExchange.vue";
import {numberFormat} from "../../../functions";

export default {
    methods: {numberFormat},
    components: {CashTransactionDollarExchange, DeleteBtn, Filters, CashTransactionModal, Pagination, Head, Link},
    props: ['cashTransactions', 'filterParams']
}
</script>
