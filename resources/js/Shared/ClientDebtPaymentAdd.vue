<template>
    <div class="row main-wrapper">
        <div class="col-12">
            <numeric-field
                   v-model="form.amount"
                   @keydown.enter="submit"
                   :class="{'is-invalid': form.errors.amount}"
                   class="form-control form-control-sm"
                   placeholder="сумма" />
        </div>
        <div class="col-12 mt-1 btn-group">
            <button @click="submit" class="btn btn-xs btn-success">
                <i class="fa fa-plus-circle"></i>
            </button>
            <button @click="$emit('cancel')" class="btn btn-xs btn-danger">
                <span class="fa fa-times"></span>
            </button>
        </div>
    </div>
</template>

<script>
import {useForm} from "@inertiajs/inertia-vue3";
import NumericField from "./NumericField.vue";

export default {
    components: {NumericField},
    props: {
        submitUrl: String,
        maxAmount: Number
    },
    data() {
        return {
            form: useForm({
                amount: null
            })
        }
    },
    methods: {
        submit() {
            if (this.maxAmount && this.form.amount > this.maxAmount) {
                alert(`Максимальное значение суммы должно быть равно ${this.numberFormat(this.maxAmount)} сом.`);

                return;
            }

            this.form.post(this.submitUrl, {
                onSuccess: () => this.$emit('success'),
            })
        }
    }
}
</script>

<style scoped>
.main-wrapper {
    min-width: 120px;
}
</style>
