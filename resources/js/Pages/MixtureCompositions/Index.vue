<template>
    <Head>
        <title>Состав продуктов</title>
    </Head>

    <div class="content-header">
        <div class="container">
            <h1 class="m-0">Состав продуктов</h1>
        </div>
    </div>

    <div class="content">
        <div class="container">
            <div class="card">
                <div class="card-header">
                    <div class="card-tools">
                        <Link :href="route('mixture-compositions.create')" class="btn btn-success btn-sm px-3">
                            Новый состав
                        </Link>
                    </div>
                </div>
                <!-- /.card-header -->
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-bordered">
                            <thead>
                            <tr>
                                <th style="width: 10px">#</th>
                                <th>Номенклатура</th>
                                <th>Масса</th>
                                <th>Кол-во воды</th>
                                <th>Оплата за работника</th>
                                <th width="40"></th>
                            </tr>
                            </thead>
                            <tbody>
                            <tr v-for="(mixtureComposition, index) in mixtureCompositions.data">
                                <td :data-id="mixtureComposition.id">{{ ((mixtureCompositions.current_page - 1) * mixtureCompositions.per_page) + index + 1 }}</td>
                                <td>
                                    <Link :href="route('mixture-compositions.show', mixtureComposition.id)">
                                        {{mixtureComposition.nomenclature}}
                                    </Link>
                                </td>
                                <td>{{mixtureComposition.weight}} {{mixtureComposition.weight_unit}}</td>
                                <td>{{mixtureComposition.water}} {{mixtureComposition.water_unit}}</td>
                                <td>{{mixtureComposition.worker_price}}</td>
                                <td class="text-center">
                                    <Link :href="route('mixture-compositions.edit', mixtureComposition.id)">
                                        <i class="fa fa-pencil-alt"></i>
                                    </Link>
                                </td>
                            </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
                <!-- /.card-body -->

                <div class="card-footer clearfix" v-if="mixtureCompositions.links.length > 3">
                    <pagination :links="mixtureCompositions.links"/>
                </div>
            </div>
        </div>
    </div>
</template>
<script>
import {Head, Link} from "@inertiajs/inertia-vue3";
import Pagination from "../../Shared/Pagination.vue";

export default {
    components: {Pagination, Head, Link},
    props: ['mixtureCompositions'],
}
</script>
