<template>
    <div v-if="links.length > 3">
        <ul class="pagination pagination-sm m-0 float-right">
            <li v-for="(link, key) in links" class="page-item" :class="{ active: link.active , disabled: link.url === null}" :key="key">
                <Link
                    class="page-link"
                    :href="link.url"
                    v-html="link.label"/>
            </li>
        </ul>
    </div>
</template>

<script>
import {Link} from '@inertiajs/inertia-vue3'

export default {
    components: {
        Link,
    },
    props: {
        links: Array,
    },
}
</script>
