<template>
    <Head>
        <title>Номенклатура</title>
    </Head>

    <div class="content-header">
        <div class="container-fluid">
            <h5 class="m-0">Номенклатура</h5>
        </div>
    </div>

    <div class="content">
        <div class="container-fluid">
            <div class="card">
                <div class="card-header">
                    <div class="row">
                        <div class="col-6 col-sm-7 col-md-7 text-left d-none d-sm-flex">
                            <nomenclature-change-markups />
                        </div>
                        <div class="col-12 col-sm-5 col-md-5 text-right">
                            <div class="card-tools">
                                <Link :href="route('nomenclatures.create')" class="btn btn-success btn-sm px-3">
                                    Новая номенклатура
                                </Link>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- /.card-header -->
                <div class="card-body">
                    <nomenclature-change-markups
                        class="d-flex mb-3 d-sm-none"
                    />

                    <div class="table-responsive">
                        <table class="table table-bordered  text-nowrap">
                            <thead>
                            <tr>
                                <th style="width: 10px">#</th>
                                <th>Наименование</th>
                                <th>Себестоимость</th>
                                <th>Курс $</th>
                                <th>Тип номенклатуры</th>
                                <th title="Единица измерения" class="text-center">Ед. изм.</th>
                                <th width="40"></th>
                            </tr>
                            </thead>
                            <tbody>
                            <tr v-for="(nomenclature, index) in nomenclatures.data">
                                <td :data-id="nomenclature.id">
                                    {{ ((nomenclatures.current_page - 1) * nomenclatures.per_page) + index + 1 }}
                                </td>
                                <td>{{ nomenclature.name }}</td>
                                <td>
                                    {{ numberFormat(nomenclature.price) }} сом.
                                    <span v-if="nomenclature.is_price_manual" class="text-danger">(расчет вручную)</span>
                                </td>
                                <td>{{ numberFormat(nomenclature.dollar_exchange_rate) }}</td>
                                <td>{{ $page.props.shared.nomenclatureTypes[nomenclature.type] }}</td>
                                <td class="text-center">{{ nomenclature.unit }}</td>
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
import Pagination from "@/Shared/Pagination.vue";
import NomenclatureChangeMarkups from "@/Shared/NomenclatureChangeMarkups.vue";

export default {
    components: {NomenclatureChangeMarkups, Pagination, Head, Link},
    props: ['nomenclatures']
}
</script>
