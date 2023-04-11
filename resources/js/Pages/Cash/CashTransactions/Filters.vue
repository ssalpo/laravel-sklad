<template>
    <form @submit.prevent="search">
        <div class="row mb-3">
            <div class="col-12 col-sm-3 mb-2 mb-sm-0">
                <VueDatePicker
                    placeholder="Выберите месяц"
                    class="date-picker-sm"
                    cancel-text="Отменить"
                    select-text="Выбрать"
                    v-model="filter.date"
                    locale="ru-RU"
                    format="dd-MM-yyyy"
                    disable-time-picker
                />
            </div>

            <div class="col-12 col-sm-3 mb-2 mb-sm-0">
                <select2
                    v-model="filter.type"
                    :options="[{id: 1, name: 'Приход'}, {id: 2, name: 'Уход'},]"
                    class="form-control-sm"
                    placeholder="Выберите тип транзакции"
                />
            </div>

            <div class="col-12 col-sm-3 mb-2 mb-sm-0">
                <select2-cash-transaction-status-list
                    v-model="filter.status"
                    class="form-control-sm"
                />
            </div>

            <div class="col-12 col-sm-3">
                <button class="btn btn-sm btn-primary mr-1" type="submit">
                    <span class="fa fa-search"></span>
                </button>
                <button v-if="isFiltered" type="button" class="btn btn-sm btn-danger" @click="reset">
                    <span class="fa fa-times"></span>
                </button>
            </div>
        </div>
    </form>
</template>

<script>
import {useForm} from "@inertiajs/inertia-vue3";
import size from "lodash/size";
import VueDatePicker from '@vuepic/vue-datepicker';
import '@vuepic/vue-datepicker/dist/main.css'
import Select2 from "../../../Shared/Select2.vue";
import Select2CashTransactionStatusList from "../../../Shared/Select2CashTransactionStatusList.vue";

export default {
    components: {Select2CashTransactionStatusList, Select2, VueDatePicker},
    props: ['filterParams'],
    data() {
        return {
            filter: useForm({
                date: this.filterParams?.date,
                status: this.filterParams?.status,
                type: this.filterParams?.type,
            })
        }
    },
    computed: {
        isFiltered() {
            return size(this.filterParams);
        }
    },
    methods: {
        search() {
            this.filter.get(route('cash-transactions.index'))
        },
        reset() {
            this.$inertia.visit(route('cash-transactions.index'));
        }
    }
}
</script>
