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

                    <div class="row">
                        <div class="col-lg-4 col-sm-6 col-12">

                            <div class="small-box bg-info">
                                <div class="inner">
                                    <h3>{{ numberFormat(todayProfit) }} с.</h3>
                                    <p>За сегодня</p>
                                </div>
                                <div class="icon">
                                    <i class="fa fa-chart-bar"></i>
                                </div>
                            </div>
                        </div>

                        <div class="col-lg-4 col-sm-6 col-12">

                            <div class="small-box bg-success">
                                <div class="inner">
                                    <h3>{{ numberFormat(monthProfit) }} с.</h3>
                                    <p>За месяц</p>
                                </div>
                                <div class="icon">
                                    <i class="fa fa-chart-line"></i>
                                </div>
                            </div>
                        </div>

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
                                <td>{{ nomenclature.name }}</td>
                                <td>{{ numberFormat(nomenclature.profit) }} сом.</td>
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
                                <td>{{ nomenclature.name }}</td>
                                <td>{{ numberFormat(nomenclature.profit) }} сом.</td>
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
