<template>
    <Head>
        <title>Аналитика</title>
    </Head>

    <div class="content-header">
        <div class="container">
            <h1 class="m-0">Аналитика</h1>
        </div>
    </div>

    <div class="content">
        <div class="container">
            <div class="card">
                <div class="card-body">
                    <h5 class="mb-3 mt-2">Прибыль</h5>
                    <div class="table-responsive">
                        <table class="table table-bordered">
                            <thead>
                            <tr>
                                <th width="50%">За сегодня</th>
                                <th>За месяц</th>
                            </tr>
                            </thead>
                            <tbody>
                            <tr>
                                <td>{{todayProfit}} сом.</td>
                                <td>{{monthProfit}} сом.</td>
                            </tr>
                            </tbody>
                        </table>
                    </div>

                    <h5 class="mb-3 mt-4">Прибыль по товарам за сегодня</h5>
                    <div class="table-responsive">
                        <table class="table table-bordered">
                            <thead>
                            <tr>
                                <th width="50%">Номенклатура</th>
                                <th>Прибыль</th>
                            </tr>
                            </thead>
                            <tbody>
                            <tr v-for="nomenclature in todayNomenclatureProfits">
                                <td>{{nomenclature.name}}</td>
                                <td>{{nomenclature.profit}} сом.</td>
                            </tr>
                            </tbody>
                        </table>
                    </div>

                    <h5 class="mb-3 mt-4">Прибыль по товарам за месяц</h5>
                    <div class="table-responsive">
                        <table class="table table-bordered">
                            <thead>
                            <tr>
                                <th width="50%">Номенклатура</th>
                                <th>Прибыль</th>
                            </tr>
                            </thead>
                            <tbody>
                            <tr v-for="nomenclature in monthNomenclatureProfits">
                                <td>{{nomenclature.name}}</td>
                                <td>{{nomenclature.profit}} сом.</td>
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
    props: ['todayProfit', 'monthProfit', 'todayNomenclatureProfits', 'monthNomenclatureProfits']
}
</script>
