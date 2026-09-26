<template>
    <Head><title>Склад материалов</title></Head>
    <div class="mi-page"><div class="content-header"><div class="container-fluid"><h5 class="m-0">Склад материалов — остатки</h5></div></div>
    <div class="content"><div class="container-fluid"><div class="card"><div class="card-header mi-actions"><Link :href="route('material-inventory.receipt.create')" class="btn btn-success btn-sm">Поступление</Link><Link :href="route('material-inventory.write-off.create')" class="btn btn-outline-danger btn-sm">Списание</Link><Link :href="route('material-inventory.return.create')" class="btn btn-outline-primary btn-sm">Возврат на склад</Link></div><div class="card-body">
        <form class="row mb-3" @submit.prevent="filter"><div class="col-md-4"><input v-model="form.search" class="form-control" placeholder="Поиск материала"></div><div class="col-md-2"><button class="btn btn-primary">Найти</button></div></form>
        <div class="table-responsive mi-table-wrap"><table class="table table-bordered mi-mobile-table"><thead><tr><th>Материал</th><th>Ед.</th><th>Приход</th><th>Расход</th><th>Остаток</th></tr></thead><tbody><tr v-for="material in materials" :key="material.id"><td data-label="Материал">{{ material.name }}</td><td data-label="Ед.">{{ material.unit }}</td><td data-label="Приход">{{ material.incoming }}</td><td data-label="Расход">{{ material.outgoing }}</td><td data-label="Остаток" class="font-weight-bold">{{ material.balance }}</td></tr><tr v-if="!materials.length"><td colspan="5" class="text-center text-muted">Материалы не найдены.</td></tr></tbody></table></div>
    </div></div></div></div></div>
</template>
<script>
import {Head, Link} from '@inertiajs/inertia-vue3';
export default {components: {Head, Link}, props: ['materials', 'filters'], data() { return {form: {search: this.filters?.search || ''}} }, methods: {filter() { this.$inertia.get(route('material-inventory.balances'), this.form, {preserveState: true}) }}};
</script>
