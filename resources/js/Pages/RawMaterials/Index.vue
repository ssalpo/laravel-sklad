<template>
    <Head>
        <title>Покупка сырья</title>
    </Head>

    <div class="content-header">
        <div class="container-fluid">
            <h5 class="m-0">Покупка сырья</h5>
        </div>
    </div>

    <div class="content">
        <div class="container-fluid">

            <div class="row" v-if="totalPaid > 0 || totalDebits > 0">
                <div class="col-lg-4 col-sm-6 col-12" v-if="totalPaid > 0">
                    <div class="small-box bg-info">
                        <div class="inner"><h3>$ {{totalPaid}}</h3>
                            <p>Общая сумма покупок сырья</p></div>
                        <div class="icon"><i class="fa fa-chart-bar"></i></div>
                    </div>
                </div>

                <div class="col-lg-4 col-sm-6 col-12" v-if="totalDebits > 0">
                    <div class="small-box bg-danger">
                        <div class="inner"><h3>$ {{totalDebits}}</h3>
                            <p>Общий остаток для погашения</p></div>
                        <div class="icon"><i class="fa fa-chart-bar"></i></div>
                    </div>
                </div>
            </div>

            <div class="card">
                <div class="card-header">
                    <div class="card-tools">
                        <Link :href="route('raw-materials.create')" class="btn btn-success btn-sm px-3">
                            Добавить
                        </Link>
                    </div>
                </div>
                <!-- /.card-header -->
                <div class="card-body">
                    <filters
                        :filter-params="filterParams"
                    />

                    <div class="table-responsive">
                        <table class="table table-bordered  text-nowrap">
                            <thead>
                            <tr>
                                <th style="width: 10px">#</th>
                                <th>Номенклатура</th>
                                <th>Клиент</th>
                                <th>Кол-во</th>
                                <th>Цена</th>
                                <th>Сумма покупки</th>
                                <th>Остаток погашения</th>
                                <th>Дата покупки</th>
                                <th width="40"></th>
                            </tr>
                            </thead>
                            <tbody>
                            <tr :class="{'table-warning': rawMaterial.total_amount > rawMaterial.payments_sum_amount}" v-for="(rawMaterial, index) in rawMaterials.data">
                                <td>{{ ((rawMaterials.current_page - 1) * rawMaterials.per_page) + index + 1 }}</td>
                                <td>
                                    <Link :href="route('raw-materials.raw-material-payments.index', rawMaterial.id)">
                                        {{ rawMaterial.nomenclature.name }}
                                    </Link>
                                </td>
                                <td>{{ rawMaterial.client.name }}</td>
                                <td>{{ rawMaterial.quantity }}</td>
                                <td>${{ rawMaterial.price }}</td>
                                <td>${{ rawMaterial.total_amount }}</td>
                                <td>${{ rawMaterial.total_amount - rawMaterial.payments_sum_amount }}</td>
                                <td>{{ rawMaterial.created_at }}</td>
                                <td class="text-center">
                                    <Link class="btn btn-sm btn-outline-primary mr-2" :href="route('raw-materials.edit', rawMaterial.id)">
                                        <i class="fa fa-pencil-alt"></i>
                                    </Link>

                                    <delete-btn
                                        :url="route('raw-materials.destroy', rawMaterial.id)"
                                    />
                                </td>
                            </tr>

                            </tbody>
                        </table>
                    </div>
                </div>
                <!-- /.card-body -->

                <div class="card-footer clearfix" v-if="rawMaterials.links.length > 3">
                    <pagination :links="rawMaterials.links"/>
                </div>
            </div>
        </div>
    </div>
</template>
<script>
import {Head, Link} from "@inertiajs/inertia-vue3";
import Pagination from "../../Shared/Pagination.vue";
import DeleteBtn from "../../Shared/DeleteBtn.vue";
import Filters from "./Filters.vue";

export default {
    components: {Filters, DeleteBtn, Pagination, Head, Link},
    props: ['rawMaterials', 'totalDebits', 'totalPaid', 'filterParams']
}
</script>
