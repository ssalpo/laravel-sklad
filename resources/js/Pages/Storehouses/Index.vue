<template>
    <Head>
        <title>Остатки</title>
    </Head>

    <div class="content-header">
        <div class="container-fluid">
            <h5 class="m-0">Остатки</h5>
        </div>
    </div>

    <div class="content">
        <div class="container-fluid">
            <div class="card">
                <div class="card-body">
                    <h5 class="mb-3">Остатки по товарам</h5>
                    <form v-if="usesWarehouseMovements" class="row mb-3" @submit.prevent="filter">
                        <div class="col-md-4 mb-2">
                            <input v-model="form.search" class="form-control" placeholder="Поиск номенклатуры">
                        </div>
                        <div class="col-md-3 mb-2">
                            <select v-model="form.type" class="form-control">
                                <option value="">Все типы</option>
                                <option v-for="(label, id) in nomenclatureTypes" :value="id">{{ label }}</option>
                            </select>
                        </div>
                        <div class="col-md-2 mb-2"><button class="btn btn-primary">Фильтр</button></div>
                    </form>
                    <div class="table-responsive">
                        <table class="table table-bordered  text-nowrap">
                            <thead>
                            <tr>
                                <th width="50%">Номенклатура</th>
                                <template v-if="usesWarehouseMovements">
                                    <th>Тип</th>
                                    <th>Единица</th>
                                    <th>Приход</th>
                                    <th>Расход</th>
                                </template>
                                <th>Остаток</th>
                            </tr>
                            </thead>
                            <tbody>
                            <tr v-for="nomenclature in nomenclatureBalances">
                                <td>{{nomenclature.name}}</td>
                                <template v-if="usesWarehouseMovements">
                                    <td>{{ nomenclatureTypes[nomenclature.type] }}</td>
                                    <td>{{ $page.props.shared.unitLabels[nomenclature.unit] }}</td>
                                    <td>{{ nomenclature.incoming }}</td>
                                    <td>{{ nomenclature.outgoing }}</td>
                                    <td>{{ nomenclature.quantity }}</td>
                                </template>
                                <td v-else>{{nomenclature.quantity - (nomenclatureWithdraws[nomenclature.id] || 0)}} {{$page.props.shared.unitLabels[nomenclature.unit]}}</td>
                            </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

</template>
<script>
import {Head, Link} from "@inertiajs/inertia-vue3";

export default {
    components: {Head, Link},
    props: ['nomenclatureBalances', 'nomenclatureWithdraws', 'filterParams', 'nomenclatureTypes', 'usesWarehouseMovements'],
    data() {
        return {form: {search: this.filterParams?.search || '', type: this.filterParams?.type || ''}}
    },
    methods: {
        filter() { this.$inertia.get(route('storehouses.index'), this.form, {preserveState: true}) }
    }
}
</script>
