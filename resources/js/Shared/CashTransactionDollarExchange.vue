<template>
    <div v-if="isEdit" class="row main-wrapper">
        <div class="col-12">
            <numeric-field :precision="4"
                           @keydown.enter="submit"
                           class="form-control form-control-sm"
                           :class="{'is-invalid': form.errors.dollar_exchange_rate}"
                           placeholder="Курс 11.15"
                           v-model.number="form.dollar_exchange_rate" />
        </div>
        <div class="col-12 mt-1 btn-group">
            <button @click="submit"
                    :disabled="!form.dollar_exchange_rate || form.processing"
                    class="btn btn-xs btn-success">
                <i class="fa fa-plus-circle"></i>
            </button>
            <button @click="cancel" class="btn btn-xs btn-danger">
                <span class="fa fa-times"></span>
            </button>
        </div>
    </div>

    <div v-if="!isEdit" @click="isEdit = !isEdit">
        ${{ numberFormat(cashTransaction.amount_in_dollar) }}, <small>по курсу {{numberFormat(cashTransaction.dollar_exchange_rate)}}</small>
    </div>
</template>

<script>
import {useForm} from "@inertiajs/inertia-vue3";
import NumericField from "./NumericField.vue";
import {numberFormat} from "../functions";

export default {
    name: 'cash-transaction-dollar-exchange',
    components: {NumericField},
    props: {
        cashTransaction: {
            type: Object,
            required: true
        }
    },
    data() {
        return {
            isEdit: false,
            form: useForm({
                dollar_exchange_rate: null
            })
        }
    },
    methods: {
        numberFormat,
        submit() {
            this.form.post(route('cash-transaction.dollar-exchange', this.cashTransaction.id), {
                onSuccess: () => {
                    this.isEdit = false;
                    this.form.reset();
                },
                preserveState: true,
                preserveScroll: true
            })
        },
        cancel() {
            this.isEdit = !this.isEdit;

            this.$emit('cancel');
        }
    }
}
</script>

<style scoped>
.main-wrapper {
    min-width: 120px;
}
</style>
