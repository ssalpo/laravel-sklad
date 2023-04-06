<template>
    <div class="container py-5">
        <h1>Select2 implementation</h1>

        <hr>
            <h4>Selected value: {{selected}}</h4>
            <button class="btn btn-sm btn-outline-primary" @click="addClient">Добавить клиента</button>
        <hr>

        <select2
            clearable
            prefetch
            :url="route('autocomplete.nomenclatures')"
            :searchable="true"
            placeholder="Выберите клиента"
            v-model="selected"
            :options="options"
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
            {id: 1, text: 'Клиент 1'},
            {id: 2, text: 'Клиент 2'},
            {id: 3, text: 'Клиент 3'},
            {id: 4, text: 'Клиент 4'},
            {id: 5, text: 'Клиент 5'},
            {id: 6, text: 'Клиент 6'},
            {id: 7, text: 'Клиент 7'},
            {id: 8, text: 'Клиент 8'},
            {id: 9, text: 'Клиент 9'},
        ],
        selected: 2
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
