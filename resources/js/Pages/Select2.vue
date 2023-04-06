<template>
    <div class="container py-5">
        <h1>Select2 implementation</h1>

        <hr>
            <h4>Selected value: {{selected}}</h4>
            <button class="btn btn-sm btn-outline-primary" @click="addClient">Добавить клиента</button>
        <hr>

        <select2
            clearable
            :url="route('autocomplete.nomenclatures')"
            searchable
            ajax
            placeholder="Выберите клиента"
            v-model="selected"
        />

    </div>
</template>

<script>

import AuthLayout from "@/Layouts/AuthLayout.vue";
import Select2 from "@/Shared/Select2.vue";
export default {
    layout: AuthLayout,
    components: {Select2},
    data: () => ({
        options: [
            {id: 1, name: 'Клиент 1'},
            {id: 2, name: 'Клиент 2'},
            {id: 3, name: 'Клиент 3'},
            {id: 4, name: 'Клиент 4'},
            {id: 5, name: 'Клиент 5'},
            {id: 6, name: 'Клиент 6'},
            {id: 7, name: 'Клиент 7'},
            {id: 8, name: 'Клиент 8'},
            {id: 9, name: 'Клиент 9'},
        ],
        selected: 3
    }),
    methods: {
        addClient() {
            this.options.push({id: 10, text: 'Клиент 10'})
        }
    }
}

// options
// v-model
// prefetch
// url
// value
// text-key
// id-key
// searchable
// disabled-values
// placeholder
// search placeholder
// value-type id, object
// clearable
// templateSelection

// works with 3 types of data
// 1. from options props
// 2. prefetched from server
// 3. search from server

</script>

<style scoped>
</style>
