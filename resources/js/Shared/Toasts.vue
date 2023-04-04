<template></template>
<script>
import {useToast} from 'vue-toastification';
import {Inertia} from "@inertiajs/inertia";
import {usePage} from "@inertiajs/inertia-vue3";

const toast = useToast();
const page = usePage();

Inertia.on('finish', () => {
    if (page.props.value.toast?.message) {
        toast(page.props.value.toast.message, {
            type: page.props.value.toast.type
        })
    }
});
</script>
