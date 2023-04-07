<template>
    <select2
        clearable
        prefetch
        :url="route('autocomplete.clients')"
        placeholder="Выберите клиента"
        v-bind="$attrs"
    />
</template>

<script>
import Select2 from "./Select2.vue";

export default {
    name: "select2-clients",
    components: {Select2}
}
</script>
