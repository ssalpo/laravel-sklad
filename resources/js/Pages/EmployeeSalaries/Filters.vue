<template>
    <form @submit.prevent="search">
        <div class="row mb-3">
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

export default {
    components: {VueDatePicker},
    props: ['filterParams', 'employeeId'],
    data() {
        return {
            filter: useForm({
                date: this.filterParams?.date,
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
            this.filter.get(route('employees.employee-salaries.index', {employee: this.employeeId}))
        },
        reset() {
            this.$inertia.visit(route('employees.employee-salaries.index', {employee: this.employeeId}));
        }
    }
}
</script>
