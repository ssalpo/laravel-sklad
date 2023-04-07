<template>
    <select2
        clearable
        prefetch
        :url="route('autocomplete.nomenclatures')"
        placeholder="Выберите номенклатуру"
        v-bind="$attrs"
    />
</template>

<script>
import Select2 from "./Select2.vue";

export default {
    name: "select2-nomenclatures",
    components: {Select2}
}
</script>
