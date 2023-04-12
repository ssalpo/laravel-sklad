<template>
    <select2
        clearable
        prefetch
        :url="route('autocomplete.orders', params)"
        placeholder="Выберите заказ"
        v-bind="$attrs"
    />
</template>

<script>
import Select2 from "./Select2.vue";

export default {
    name: "select2-orders",
    components: {Select2},
    props: {
        params: Object
    }
}
</script>
