<template>
    <select2
        :options="statusList"
        placeholder="Выберите статус"
    />
</template>

<script>
import Select2 from "./Select2.vue";
import {CASH_TRANSACTION_STATUS_LABELS} from "../Constants/cashTransaction";
import map from "lodash/map";

export default {
    name: "select2-cash-transaction-status-list",
    components: {Select2},
    computed: {
        statusList() {
            return map(CASH_TRANSACTION_STATUS_LABELS, (v, k) => ({id: k, name: v}));
        }
    }
}
</script>
