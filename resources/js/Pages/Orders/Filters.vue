<template>
    <form @submit.prevent="search">
        <div class="row mb-3">
            <div class="col-12 col-sm-3 col-md-3 mb-2 mb-sm-0">
                <input
                    type="text"
                    class="form-control form-control-sm"
                    v-model="filter.id"
                    placeholder="Номер заявки"
                />
            </div>
            <div class="col-12 col-sm-3 col-md-3 mb-2 mb-sm-0">
                <select2-users
                    v-model.number="filter.user"
                    class="form-control-sm"
                />
            </div>

            <div class="col-12 col-sm-3 col-md-3 mb-2 mb-sm-0">
                <select2-clients
                    v-model.number="filter.client"
                    class="form-control-sm"
                />
            </div>

            <div class="col-12 col-sm-3 col-md-3">
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
import Select2Clients from "../../Shared/Select2Clients.vue";
import Select2Users from "../../Shared/Select2Users.vue";

export default {
    props: ['filterParams'],
    components: {Select2Users, Select2Clients, Select2Nomenclatures},
    data() {
        return {
            filter: useForm({
                id: this.filterParams?.id,
                user: this.filterParams?.user,
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
            this.filter.get(route('orders.index'))
        },
        reset() {
            this.$inertia.visit(route('orders.index'));
        }
    }
}
</script>
