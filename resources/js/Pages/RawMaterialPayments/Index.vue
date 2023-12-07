<template>
    <Head>
        <title>История платежей</title>
    </Head>

    <div class="content-header">
        <div class="container-fluid">
            <h5 class="m-0">История платежей</h5>
        </div>
    </div>

    <div class="content">
        <div class="container-fluid">
            <div class="row" v-if="totalDebits > 0">
                <div class="col-lg-4 col-sm-6 col-12">
                    <div class="small-box bg-info">
                        <div class="inner"><h3>$ {{totalDebits}}</h3>
                            <p>Остаток для погашения</p></div>
                        <div class="icon"><i class="fa fa-chart-bar"></i></div>
                    </div>
                </div>
            </div>

            <div class="card">
                <div class="card-header" v-if="totalDebits > 0">
                    <div class="float-left">
                        <back-btn :href="route('raw-materials.index')" />
                    </div>
                    <div class="float-right">
                        <Link :href="route('raw-materials.raw-material-payments.create', rawMaterial.id)" class="btn btn-success btn-sm px-3">
                            Добавить
                        </Link>
                    </div>
                </div>
                <!-- /.card-header -->
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-bordered  text-nowrap">
                            <thead>
                            <tr>
                                <th style="width: 10px">#</th>
                                <th>Дата</th>
                                <th>Сумма</th>
                                <th>Комментарий</th>
                                <th width="40"></th>
                            </tr>
                            </thead>
                            <tbody>
                            <tr :class="{'table-danger': rawMaterialPayment.total_amount > rawMaterialPayment.payments_sum_amount}" v-for="(rawMaterialPayment, index) in rawMaterialPayments.data">
                                <td>{{ ((rawMaterialPayments.current_page - 1) * rawMaterialPayments.per_page) + index + 1 }}</td>
                                <td>{{ rawMaterialPayment.created_at }}</td>
                                <td>${{ rawMaterialPayment.amount }}</td>
                                <td>{{ rawMaterialPayment.comment }}</td>
                                <td class="text-center">
                                    <Link class="btn btn-sm btn-outline-primary mr-2" :href="route('raw-materials.raw-material-payments.edit', {'raw_material': rawMaterialPayment.raw_material_id, 'raw_material_payment': rawMaterialPayment.id})">
                                        <i class="fa fa-pencil-alt"></i>
                                    </Link>

                                    <delete-btn
                                        :url="route('raw-materials.raw-material-payments.destroy' , {'raw_material': rawMaterialPayment.raw_material_id, 'raw_material_payment': rawMaterialPayment.id})"
                                    />
                                </td>
                            </tr>

                            </tbody>
                        </table>
                    </div>
                </div>
                <!-- /.card-body -->

                <div class="card-footer clearfix" v-if="rawMaterialPayments.links.length > 3">
                    <pagination :links="rawMaterialPayments.links"/>
                </div>
            </div>
        </div>
    </div>
</template>
<script>
import {Head, Link} from "@inertiajs/inertia-vue3";
import Pagination from "../../Shared/Pagination.vue";
import DeleteBtn from "../../Shared/DeleteBtn.vue";
import BackBtn from "../../Shared/BackBtn.vue";

export default {
    components: {BackBtn, DeleteBtn, Pagination, Head, Link},
    props: ['rawMaterial', 'rawMaterialPayments', 'totalDebits']
}
</script>
