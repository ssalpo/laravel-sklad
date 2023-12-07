<script>
import {Link} from "@inertiajs/inertia-vue3";

export default {
    components: {Link},
    props: ['href']
}
</script>

<template>
    <Link class="btn btn-sm btn-outline-info mr-2" :href="href">
        <i class="fa fa-arrow-left"></i>
    </Link>
</template>
