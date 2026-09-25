<template>
    <Head><title>{{ inventory?.id ? `Инвентаризация #${inventory.id}` : 'Новая инвентаризация' }}</title></Head>
    <div class="content-header"><div class="container-fluid"><h5 class="m-0">{{ inventory?.id ? `Инвентаризация #${inventory.id}` : 'Новая инвентаризация' }}</h5></div></div>
    <div class="content"><div class="container-fluid"><div class="card card-primary">
        <form @submit.prevent="submit"><div class="card-body">
            <div v-if="inventory?.id" class="mb-3 text-muted">Статус: {{ inventory.status_label }} · Создана: {{ inventory.created_at }}<span v-if="inventory.posted_at"> · Проведена: {{ inventory.posted_at }}</span></div>
            <div class="form-group col-md-8 px-0"><label>Комментарий</label><input v-model.trim="form.comment" :disabled="posted" class="form-control" :class="{'is-invalid': errors.comment}"><div v-if="errors.comment" class="invalid-feedback">{{ errors.comment }}</div></div>
            <div v-if="!posted" class="row align-items-end mb-3"><div class="col-md-6"><label>Добавить товар</label><custom-select full searchable :options="availableNomenclatures" v-model.number="selectedNomenclatureId" label-key="name" /></div><div class="col-md-2"><button type="button" class="btn btn-outline-primary" :disabled="!selectedNomenclatureId" @click="addItem">Добавить</button></div></div>
            <div v-if="errors.items" class="alert alert-danger">{{ errors.items }}</div>
            <div class="table-responsive"><table class="table table-bordered"><thead><tr><th>Номенклатура</th><th>Учётный остаток</th><th>Фактический остаток</th><th>Расхождение</th><th v-if="!posted"></th></tr></thead>
                <tbody><tr v-for="(item, index) in form.items" :key="item.nomenclature_id"><td>{{ item.nomenclature }} <small class="text-muted">{{ item.unit }}</small></td><td>{{ item.book_quantity }}</td><td><numeric-field v-if="!posted" v-model="item.actual_quantity" type="number" class="form-control" :class="{'is-invalid': errors[`items.${index}.actual_quantity`]}" /><span v-else>{{ item.actual_quantity }}</span><div v-if="errors[`items.${index}.actual_quantity`]" class="invalid-feedback">{{ errors[`items.${index}.actual_quantity`] }}</div></td><td :class="difference(item) === '0.000000' ? '' : 'font-weight-bold'">{{ difference(item) }}</td><td v-if="!posted"><button type="button" class="btn btn-sm btn-outline-danger" @click="removeItem(index)"><i class="fa fa-trash"></i></button></td></tr><tr v-if="!form.items.length"><td colspan="5" class="text-muted text-center">Добавьте товары для пересчёта.</td></tr></tbody>
            </table></div>
        </div><div class="card-footer text-right"><button v-if="!posted" type="submit" class="btn btn-primary" :disabled="form.processing">{{ form.processing ? 'Сохранение...' : (inventory?.id ? 'Сохранить' : 'Создать черновик') }}</button><button v-if="inventory?.id && !posted" type="button" class="btn btn-success ml-2" :disabled="form.processing" @click="postInventory">Провести</button><delete-btn v-if="inventory?.id && !posted" :url="route('warehouse-inventories.destroy', inventory.id)" btn-style="btn-outline-danger" btn-size="btn" class="ml-2" /><Link :href="route('warehouse-inventories.index')" class="btn btn-default ml-2">К списку</Link></div></form>
    </div></div></div>
</template>
<script>
import {Head, Link, useForm} from '@inertiajs/inertia-vue3';
import CustomSelect from '../../Shared/CustomSelect.vue';
import NumericField from '../../Shared/NumericField.vue';
import DeleteBtn from '../../Shared/DeleteBtn.vue';
export default {
    components: {Head, Link, CustomSelect, NumericField, DeleteBtn}, props: ['inventory', 'nomenclatures', 'errors'],
    data() { return {selectedNomenclatureId: null, form: useForm({comment: this.inventory?.comment || '', items: (this.inventory?.items || []).map(item => ({...item}))})} },
    computed: { posted() { return this.inventory?.status === 'posted' }, availableNomenclatures() { return this.nomenclatures.filter(item => !this.form.items.some(row => row.nomenclature_id === item.id)) } },
    methods: {
        addItem() { const item = this.nomenclatures.find(item => item.id === this.selectedNomenclatureId); if (!item) return; this.form.items.push({nomenclature_id: item.id, nomenclature: item.name, unit: item.unit, book_quantity: 'Будет зафиксирован при сохранении', actual_quantity: 0}); this.selectedNomenclatureId = null },
        removeItem(index) { this.form.items.splice(index, 1) },
        difference(item) { if (typeof item.book_quantity !== 'string' || item.book_quantity.includes('Будет')) return '—'; return (Number(item.actual_quantity || 0) - Number(item.book_quantity)).toFixed(6) },
        submit() { const options = {preserveScroll: true}; this.inventory?.id ? this.form.put(route('warehouse-inventories.update', this.inventory.id), options) : this.form.post(route('warehouse-inventories.store'), options) },
        postInventory() { if (!confirm('Провести инвентаризацию? После проведения её нельзя изменить.')) return; this.$inertia.post(route('warehouse-inventories.post', this.inventory.id)) }
    }
}
</script>
