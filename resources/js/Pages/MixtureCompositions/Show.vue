<template>
    <Head>
        <title>Состав: {{mixtureComposition.nomenclature}}</title>
    </Head>

    <div class="content-header">
        <div class="container">
            <h1 class="m-0">Состав: {{mixtureComposition.nomenclature}}</h1>
        </div>
    </div>

    <div class="content">
        <div class="container">
            <div class="card">
                <!-- /.card-header -->
                <div class="card-body">
                    <h5>Данные состава</h5>

                    <div class="table-responsive table-hover mb-4 mt-3">
                        <table width="350" border="1">
                            <tr>
                                <td class="p-1">Общий объём состава</td>
                                <td class="p-1">{{mixtureComposition.water}} {{mixtureComposition.waterUnit}}</td>
                            </tr>
                            <tr>
                                <td class="p-1">Цена работника за единицу</td>
                                <td class="p-1">${{numberFormat(mixtureComposition.worker_price, 4)}}</td>
                            </tr>
                            <tr>
                                <td class="p-1">
                                    <b>Итого сумма за единицу состава</b>
                                </td>
                                <td class="p-1">
                                    ${{numberFormat(totalSum, 3)}}
                                </td>
                            </tr>
                        </table>
                    </div>

                    <div class="row pb-2 mb-md-0">
                        <div class="col-sm">
                            <h5>Составные элементы</h5>
                        </div>
                        <div class="col-sm text-sm-left text-md-right">
                            <Link class="btn btn-sm btn-outline-primary" :href="route('mixture-composition-items.create', mixtureComposition.id)">добавить</Link>
                        </div>
                    </div>

                    <div class="table-responsive">
                        <table class="table table-bordered  text-nowrap">
                            <thead>
                            <tr>
                                <th style="width: 10px">#</th>
                                <th>Номенклатура</th>
                                <th>Количество</th>
                                <th>Сумма</th>
                                <th width="100"></th>
                            </tr>
                            </thead>
                            <tbody>
                            <tr v-for="(mixtureCompositionItem, index) in mixtureCompositionItemsNotEndResults">
                                <td :data-id="mixtureCompositionItem.id">{{ index + 1 }}</td>
                                <td>{{mixtureCompositionItem.nomenclature}}</td>
                                <td>{{mixtureCompositionItem.quantity}} {{mixtureCompositionItem.unit}}</td>
                                <td>${{numberFormat(mixtureCompositionItem.price, 3)}}</td>
                                <td class="text-center">
                                    <Link class="btn btn-sm btn-link" :href="route('mixture-composition-items.edit', {'mixture_composition' : mixtureComposition.id, 'mixture_composition_item' : mixtureCompositionItem.id})">
                                        <i class="fa fa-pencil-alt"></i>
                                    </Link>
                                    <Link method="delete" as="button"
                                          type="button"
                                          preserve-sscroll
                                          preserve-state
                                          :href="route('mixture-composition-items.destroy', { 'mixture_composition': this.mixtureComposition.id, 'mixture_composition_item': mixtureCompositionItem.id })"
                                          class="btn btn-sm btn-link">
                                        <i class="fa fa-trash-alt text-danger"></i>
                                    </Link>
                                </td>
                            </tr>
                            <tr>
                                <td colspan="3"></td>
                                <td>Итого: ${{numberFormat(notEndResultTotalAmount, 3)}}</td>
                                <td></td>
                            </tr>
                            </tbody>
                        </table>
                    </div>

                    <div class="row mt-3" v-if="mixtureCompositionItemsEndResults.length">
                        <div class="col-sm">
                            <h5>Составные элементы включающиеся в конечную стоимость</h5>
                        </div>
                    </div>

                    <div class="table-responsive" v-if="mixtureCompositionItemsEndResults.length">
                        <table class="table table-bordered  text-nowrap">
                            <thead>
                            <tr>
                                <th style="width: 10px">#</th>
                                <th>Номенклатура</th>
                                <th>Количество</th>
                                <th>Сумма</th>
                                <th width="100"></th>
                            </tr>
                            </thead>
                            <tbody>
                            <tr v-for="(mixtureCompositionItem, index) in mixtureCompositionItemsEndResults">
                                <td :data-id="mixtureCompositionItem.id">{{ index + 1 }}</td>
                                <td>{{mixtureCompositionItem.nomenclature}}</td>
                                <td>{{mixtureCompositionItem.quantity}} {{mixtureCompositionItem.unit}}</td>
                                <td>${{numberFormat(mixtureCompositionItem.price, 3)}}</td>
                                <td class="text-center">
                                    <Link class="btn btn-sm btn-link" :href="route('mixture-composition-items.edit', {'mixture_composition' : mixtureComposition.id, 'mixture_composition_item' : mixtureCompositionItem.id})">
                                        <i class="fa fa-pencil-alt"></i>
                                    </Link>
                                    <Link method="delete" as="button"
                                          type="button"
                                          preserve-sscroll
                                          preserve-state
                                          :href="route('mixture-composition-items.destroy', { 'mixture_composition': this.mixtureComposition.id, 'mixture_composition_item': mixtureCompositionItem.id })"
                                          class="btn btn-sm btn-link">
                                        <i class="fa fa-trash-alt text-danger"></i>
                                    </Link>
                                </td>
                            </tr>
                            <tr>
                                <td colspan="3"></td>
                                <td>Итого: ${{numberFormat(endResultTotalAmount, 3)}}</td>
                                <td></td>
                            </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
                <!-- /.card-body -->
            </div>
        </div>
    </div>
</template>
<script>
import {Head, Link} from "@inertiajs/inertia-vue3";
import Pagination from "../../Shared/Pagination.vue";

export default {
    components: {Pagination, Head, Link},
    props: ['totalSum', 'mixtureComposition'],
    computed: {
        mixtureCompositionItemsNotEndResults() {
            return this.mixtureComposition.mixtureCompositionItems.filter(
                (item) => item.end_result === false
            )
        },
        mixtureCompositionItemsEndResults() {
            return this.mixtureComposition.mixtureCompositionItems.filter(
                (item) => item.end_result === true
            )
        },
        notEndResultTotalAmount() {
            return this.mixtureCompositionItemsNotEndResults.reduce((accumulator, current) => accumulator + current.price, 0);
        },
        endResultTotalAmount() {
            return this.mixtureCompositionItemsEndResults.reduce((accumulator, current) => accumulator + current.price, 0);
        }
    }
}
</script>
