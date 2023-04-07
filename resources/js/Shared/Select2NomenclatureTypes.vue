<template>
    <select2
        clearable
        :searchable="false"
        placeholder="Выберите тип номенклатуры"
        v-bind="$attrs"
        :options="mappedTypes"
    />
</template>

<script>
import Select2 from "./Select2.vue";
import {usePage} from "@inertiajs/inertia-vue3";
import map from "lodash/map";

export default {
    name: "select2-nomenclature-types",
    components: {Select2},
    computed: {
        mappedTypes() {
            return map(usePage().props.value.shared.nomenclatureTypes, (name, id) => {
                return {id, name}
            });
        }
    }
}
</script>
