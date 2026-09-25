<template>
    <Head><title>Движения склада</title></Head>
    <div class="content-header"><div class="container-fluid"><h5 class="m-0">Движения склада</h5></div></div>
    <div class="content"><div class="container-fluid"><div class="card"><div class="card-body">
        <form class="row mb-3" @submit.prevent="filter">
            <div class="col-md-2 mb-2"><input v-model="form.from" type="date" class="form-control"></div>
            <div class="col-md-2 mb-2"><input v-model="form.to" type="date" class="form-control"></div>
            <div class="col-md-3 mb-2"><select v-model="form.nomenclature_id" class="form-control"><option value="">Номенклатура</option><option v-for="item in nomenclatures" :value="item.id">{{ item.name }}</option></select></div>
            <div class="col-md-2 mb-2"><select v-model="form.type" class="form-control"><option value="">Тип</option><option v-for="(label, value) in types" :value="value">{{ label }}</option></select></div>
            <div class="col-md-2 mb-2"><select v-model="form.direction" class="form-control"><option value="">Направление</option><option v-for="(label, value) in directions" :value="value">{{ label }}</option></select></div>
            <div class="col-md-1 mb-2"><button class="btn btn-primary">Найти</button></div>
        </form>
        <div class="table-responsive"><table class="table table-bordered text-nowrap"><thead><tr><th>Дата</th><th>Номенклатура</th><th>Тип</th><th>Приход</th><th>Расход</th><th>Источник</th><th>Комментарий</th><th>Пользователь</th></tr></thead>
            <tbody><tr v-for="movement in movements.data"><td>{{ movement.occurred_at }}</td><td>{{ movement.nomenclature }}</td><td>{{ movement.type_label }}</td><td>{{ movement.direction === 'in' ? movement.quantity + ' ' + movement.unit : '' }}</td><td>{{ movement.direction === 'out' ? movement.quantity + ' ' + movement.unit : '' }}</td><td>{{ movement.source }}</td><td>{{ movement.comment }}</td><td>{{ movement.created_by }}</td></tr></tbody>
        </table></div>
        <pagination v-if="movements.links.length > 3" :links="movements.links" />
    </div></div></div></div>
</template>
<script>
import {Head} from '@inertiajs/inertia-vue3';
import Pagination from '../../Shared/Pagination.vue';
export default {
    components: {Head, Pagination}, props: ['movements', 'filters', 'nomenclatures', 'types', 'directions'],
    data() { return {form: {...this.filters}} },
    methods: { filter() { this.$inertia.get(route('warehouse-movements.index'), this.form, {preserveState: true}) } }
}
</script>
