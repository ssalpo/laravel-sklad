<template>
    <Head>
        <title>Аналитика за период</title>
    </Head>

    <div class="content-header">
        <div class="container-fluid">
            <h1 class="m-0">Аналитика за период</h1>
        </div>
    </div>

    <div class="content">
        <div class="container-fluid">
            <div class="card">
                <div class="card-body">

                    <range-filters
                        :filter-params="filterParams"
                    />

                    <h5 class="mb-3 mt-2">Прибыль за период</h5>

                    <div class="row">
                        <div class="col-lg-4 col-sm-6 col-12">

                            <div class="small-box bg-info">
                                <div class="inner">
                                    <h3>{{ numberFormat(profit) }} с.</h3>
                                    <p>Общая прибыль</p>
                                </div>
                                <div class="icon">
                                    <i class="fa fa-chart-bar"></i>
                                </div>
                            </div>
                        </div>
                    </div>

                    <h5 class="mb-3 mt-4">Прибыль по товарам за период</h5>

                    <div class="table-responsive">
                        <table class="table table-bordered  text-nowrap">
                            <thead>
                            <tr>
                                <th width="50%">Номенклатура</th>
                                <th>Продажи</th>
                                <th>Прибыль</th>
                            </tr>
                            </thead>
                            <tbody>
                            <tr v-for="nomenclatureProfit in nomenclatureProfits">
                                <td>{{ nomenclatureProfit.name }}</td>
                                <td>{{ numberFormat(nomenclatureProfit.amount) }} сом.</td>
                                <td>{{ numberFormat(nomenclatureProfit.profit) }} сом.</td>
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
import RangeFilters from "./RangeFilters.vue";

export default {
    components: {RangeFilters, Head, Link},
    props: ['profit', 'nomenclatureProfits', 'filterParams']
}
</script>
