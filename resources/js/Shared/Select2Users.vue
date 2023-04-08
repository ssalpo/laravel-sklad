<template>
    <select2
        clearable
        prefetch
        :url="route('autocomplete.users')"
        placeholder="Выберите сотрудника"
        v-bind="$attrs"
    />
</template>

<script>
import Select2 from "./Select2.vue";

export default {
    name: "select2-users",
    components: {Select2}
}
</script>
