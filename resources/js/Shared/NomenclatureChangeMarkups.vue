<template>
    <div class="row">
        <div class="col-8 col-sm-6 col-md-5">
            <numeric-field :precision="3" type="text" placeholder="Курс, прим: 11.15" v-model="dollarExchangeRate" class="form-control form-control-sm" />
        </div>
        <div class="col-4 col-sm-6 col-md-7">
            <button :disabled="dollarExchangeRate === null || dollarExchangeRate <= 0 || isMarkupChanging" class="btn btn-sm btn-outline-info" @click="changeMarkup">
                <span class="fa fa-exchange-alt"></span> <span class="d-none d-md-inline-block">Обновить себестоимость</span>
            </button>
        </div>
    </div>
</template>

<script>
import NumericField from "./NumericField.vue";

export default {
    components: {NumericField},
    data: () => ({
        dollarExchangeRate: null,
        isMarkupChanging: false,
    }),
    methods: {
        changeMarkup() {
            this.isMarkupChanging = true;

            axios.post(route('nomenclatures.change-markups'), {dollar_exchange_rate: this.dollarExchangeRate})
                .then(() => {
                    this.$inertia.visit(route('nomenclatures.index'))
                })
                .finally(() => this.isMarkupChanging = false)
        }
    }
}
</script>

<style scoped>

</style>
