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
                    <div class="table-responsive">
                        <table class="table table-bordered  text-nowrap">
                            <thead>
                            <tr>
                                <th width="50%">Номенклатура</th>
                                <th>Остаток</th>
                            </tr>
                            </thead>
                            <tbody>
                            <tr v-for="nomenclature in nomenclatureBalances">
                                <td>{{nomenclature.name}}</td>
                                <td>{{nomenclature.quantity - (nomenclatureWithdraws[nomenclature.id] || 0)}} {{$page.props.shared.unitLabels[nomenclature.unit]}}</td>
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
    props: ['nomenclatureBalances', 'nomenclatureWithdraws']
}
</script>
