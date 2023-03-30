<template>
    <Head>
        <title>Номенклатура</title>
    </Head>

    <div class="content-header">
        <div class="container">
            <h5 class="m-0">Номенклатура</h5>
        </div>
    </div>

    <div class="content">
        <div class="container">
            <div class="card">
                <div class="card-header">
                    <div class="card-tools">
                        <Link :href="route('nomenclatures.create')" class="btn btn-success btn-sm px-3">
                            Новая номенклатура
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
                                <th>Наименование</th>
                                <th>Цена покупки</th>
                                <th>Цена продажи</th>
                                <th>Тип номенклатуры</th>
                                <th title="Единица измерения">Ед. изм.</th>
                                <th width="40"></th>
                            </tr>
                            </thead>
                            <tbody>
                            <tr v-for="(nomenclature, index) in nomenclatures.data">
                                <td :data-id="nomenclature.id">{{ ((nomenclatures.current_page - 1) * nomenclatures.per_page) + index + 1 }}</td>
                                <td>{{nomenclature.name}}</td>
                                <td>{{numberFormat(nomenclature.price)}} сом.</td>
                                <td>{{numberFormat(nomenclature.price_for_sale)}} сом.</td>
                                <td>{{$page.props.shared.nomenclatureTypes[nomenclature.type]}}</td>
                                <td>{{nomenclature.unit}}</td>
                                <td class="text-center">
                                    <Link :href="route('nomenclatures.edit', nomenclature.id)">
                                        <i class="fa fa-pencil-alt"></i>
                                    </Link>
                                </td>
                            </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
                <!-- /.card-body -->

                <div class="card-footer clearfix" v-if="nomenclatures.links.length > 3">
                    <pagination :links="nomenclatures.links"/>
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
    props: ['nomenclatures'],
}
</script>
