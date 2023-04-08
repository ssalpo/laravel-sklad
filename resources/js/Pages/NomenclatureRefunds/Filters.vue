<template>
    <form @submit.prevent="search">
        <div class="row mb-3">
            <div class="col-12 col-sm-4 col-md-3 mb-2 mb-sm-0">
                <input
                    type="text"
                    class="form-control form-control-sm"
                    v-model="filter.order"
                    placeholder="Номер заявки"
                />
            </div>
            <div class="col-12 col-sm-4 col-md-3 mb-2 mb-sm-0">
                <select2-nomenclatures
                    v-model.number="filter.nomenclature"
                    class="form-control-sm"
                />
            </div>
            <div class="col-12 col-sm-4 col-md-6">
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
import Select2Nomenclatures from "../../Shared/Select2Nomenclatures.vue";
import size from "lodash/size";

export default {
    props: ['filterParams'],
    components: {Select2Nomenclatures},
    data() {
        return {
            filter: useForm({
                order: this.filterParams?.order,
                nomenclature: this.filterParams?.nomenclature,
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
            this.filter.get(route('nomenclature-refunds.index'))
        },
        reset() {
            this.$inertia.visit(route('nomenclature-refunds.index'));
        }
    }
}
</script>
