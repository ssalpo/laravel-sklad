<template>
    <div class="row">
        <div class="col-12">
            <input type="text"
                   v-model="form.amount"
                   :class="{'is-invalid': form.errors.amount}"
                   class="form-control form-control-sm"
                   placeholder="сумма"/>
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

export default {
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
