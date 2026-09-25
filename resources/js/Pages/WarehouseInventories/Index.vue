<template>
    <Head><title>Инвентаризации</title></Head>
    <div class="content-header"><div class="container-fluid"><h5 class="m-0">Инвентаризации</h5></div></div>
    <div class="content"><div class="container-fluid"><div class="card">
        <div class="card-header"><Link :href="route('warehouse-inventories.create')" class="btn btn-success btn-sm">Новая инвентаризация</Link></div>
        <div class="card-body">
            <form class="row mb-3" @submit.prevent="filter">
                <div class="col-md-2 mb-2"><input v-model="form.from" type="date" class="form-control"></div>
                <div class="col-md-2 mb-2"><input v-model="form.to" type="date" class="form-control"></div>
                <div class="col-md-3 mb-2"><select v-model="form.status" class="form-control"><option value="">Все статусы</option><option v-for="(label, value) in statuses" :value="value">{{ label }}</option></select></div>
                <div class="col-md-2 mb-2"><button class="btn btn-primary">Найти</button></div>
            </form>
            <div class="table-responsive"><table class="table table-bordered text-nowrap"><thead><tr><th>#</th><th>Статус</th><th>Позиций</th><th>Комментарий</th><th>Создал</th><th>Создана</th><th>Проведена</th><th></th></tr></thead>
                <tbody><tr v-for="inventory in inventories.data"><td>{{ inventory.id }}</td><td>{{ inventory.status_label }}</td><td>{{ inventory.items_count }}</td><td>{{ inventory.comment }}</td><td>{{ inventory.created_by }}</td><td>{{ inventory.created_at }}</td><td>{{ inventory.posted_at }}</td><td><Link :href="route('warehouse-inventories.edit', inventory.id)" class="btn btn-sm btn-outline-primary"><i class="fa fa-eye"></i></Link></td></tr></tbody>
            </table></div>
            <pagination v-if="inventories.links.length > 3" :links="inventories.links" />
        </div>
    </div></div></div>
</template>
<script>
import {Head, Link} from '@inertiajs/inertia-vue3';
import Pagination from '../../Shared/Pagination.vue';
export default {
    components: {Head, Link, Pagination}, props: ['inventories', 'filters', 'statuses'],
    data() { return {form: {...this.filters}} },
    methods: { filter() { this.$inertia.get(route('warehouse-inventories.index'), this.form, {preserveState: true}) } }
}
</script>
