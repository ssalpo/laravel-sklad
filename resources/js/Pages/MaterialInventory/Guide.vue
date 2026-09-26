<template>
    <Head><title>Справка по операциям и статусам</title></Head>
    <div class="mi-page">
        <div class="content-header"><div class="container-fluid"><h5 class="m-0">Справка по операциям и статусам</h5></div></div>
        <div class="content"><div class="container-fluid">
            <div class="card"><div class="card-body">
                <p class="text-muted mb-4">Остаток материала рассчитывается только по журналу движений. Эта страница поясняет, когда использовать каждый тип записи и как он влияет на остаток.</p>

                <h6>Типы движений материалов</h6>
                <div class="table-responsive mi-table-wrap"><table class="table table-bordered mi-mobile-table">
                    <thead><tr><th>Операция</th><th>Влияние на остаток</th><th>Когда использовать</th><th>Пример</th></tr></thead>
                    <tbody><tr v-for="item in movementTypes" :key="item.value">
                        <td data-label="Операция" class="font-weight-bold">{{ item.label }}</td>
                        <td data-label="Влияние">{{ item.direction }}</td>
                        <td data-label="Когда использовать">{{ item.description }}</td>
                        <td data-label="Пример">{{ item.example }}</td>
                    </tr></tbody>
                </table></div>

                <h6 class="mt-4">Направление движения</h6>
                <div class="row"><div v-for="item in directions" :key="item.value" class="col-md-6 mb-3"><div class="border rounded p-3 h-100"><div class="font-weight-bold">{{ item.label }}</div><div class="text-muted">{{ item.description }}</div></div></div></div>

                <h6 class="mt-2">Статусы выпуска производства</h6>
                <div class="table-responsive mi-table-wrap"><table class="table table-bordered mi-mobile-table">
                    <thead><tr><th>Статус</th><th>Что означает</th><th>Пример</th></tr></thead>
                    <tbody><tr v-for="(item, value) in productionStatuses" :key="value"><td data-label="Статус" class="font-weight-bold">{{ item.label }}</td><td data-label="Что означает">{{ item.description }}</td><td data-label="Пример">{{ item.example }}</td></tr></tbody>
                </table></div>

                <h6 class="mt-4">Статусы инвентаризации</h6>
                <div class="table-responsive mi-table-wrap"><table class="table table-bordered mi-mobile-table">
                    <thead><tr><th>Статус</th><th>Что означает</th><th>Пример</th></tr></thead>
                    <tbody><tr v-for="(item, value) in inventoryCountStatuses" :key="value"><td data-label="Статус" class="font-weight-bold">{{ item.label }}</td><td data-label="Что означает">{{ item.description }}</td><td data-label="Пример">{{ item.example }}</td></tr></tbody>
                </table></div>
            </div><div class="card-footer"><Link :href="route('material-inventory.movements')" class="btn btn-default">К журналу движений</Link></div></div>
        </div></div>
    </div>
</template>

<script>
import {Head, Link} from '@inertiajs/inertia-vue3';

export default {
    components: {Head, Link},
    props: ['movementTypes', 'directions', 'productionStatuses', 'inventoryCountStatuses'],
};
</script>
