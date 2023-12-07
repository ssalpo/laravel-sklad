<template>
    <form @submit.prevent="search">
        <div class="row mb-3">
            <div class="col-12 col-sm-3 mb-2 mb-sm-0">
                <select2-clients
                    v-model.number="filter.client"
                    class="form-control-sm"
                />
            </div>

            <div class="col-12 col-sm-3 mb-2 mb-sm-0">
                <VueDatePicker
                    placeholder="Выберите даты"
                    class="date-picker-sm"
                    cancel-text="Отменить"
                    select-text="Выбрать"
                    v-model="filter.date"
                    locale="ru-RU"
                    format="dd-MM-yyyy"
                    :enable-time-picker="false"
                    range
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
import Select2Clients from "../../Shared/Select2Clients.vue";

export default {
    components: {Select2Clients, VueDatePicker},
    props: ['filterParams', 'rawMaterialId'],
    data() {
        return {
            filter: useForm({
                date: this.filterParams?.date,
                client: this.filterParams?.client,
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
            this.filter.get(route('raw-materials.index', {raw_material: this.rawMaterialId}))
        },
        reset() {
            this.$inertia.visit(route('raw-materials.index', {raw_material: this.rawMaterialId}));
        }
    }
}
</script>
