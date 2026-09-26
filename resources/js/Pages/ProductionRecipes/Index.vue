<template>
    <Head><title>Рецептуры</title></Head>
    <div class="mi-page"><div class="content-header"><div class="container-fluid"><h5 class="m-0">Рецептуры</h5></div></div>
        <div class="content"><div class="container-fluid"><div class="card">
            <div class="card-header"><Link :href="route('production-recipes.create')" class="btn btn-success btn-sm">Новая рецептура</Link></div>
            <div class="card-body">
                <div v-for="product in products.data" :key="product.id" class="border rounded mb-3 overflow-hidden">
                    <div class="bg-light px-3 py-2 font-weight-bold">{{ product.name }}</div>
                    <div class="list-group list-group-flush">
                        <div v-for="recipe in product.recipes" :key="recipe.id" class="list-group-item d-flex flex-wrap align-items-center justify-content-between" style="gap: .75rem">
                            <div>
                                <span class="font-weight-bold mr-2">v{{ recipe.version }}</span>
                                <span :class="recipe.is_active ? 'text-success' : 'text-muted'">{{ recipe.is_active ? 'Активная версия' : 'Неактивная версия' }}</span>
                                <small class="text-muted ml-2">Выпусков: {{ recipe.runs_count }}</small>
                            </div>
                            <Link :href="route('production-recipes.edit', recipe.id)" class="btn btn-sm btn-outline-primary">Открыть</Link>
                        </div>
                    </div>
                </div>
                <div v-if="!products.data.length" class="text-center text-muted py-3">Рецептуры не найдены.</div>
                <pagination v-if="products.links.length > 3" :links="products.links" />
            </div>
        </div></div></div>
    </div>
</template>

<script>
import {Head, Link} from '@inertiajs/inertia-vue3';
import Pagination from '../../Shared/Pagination.vue';

export default {
    components: {Head, Link, Pagination},
    props: ['products'],
};
</script>
