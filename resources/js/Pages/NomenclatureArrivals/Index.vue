<template>
    <Head>
        <title>Приход</title>
    </Head>

    <div class="content-header">
        <div class="container-fluid">
            <h5 class="m-0">Приход</h5>
        </div>
    </div>

    <div class="content">
        <div class="container-fluid">
            <div class="card">
                <div class="card-header">
                    <div class="card-tools">
                        <Link :href="route('nomenclature-arrivals.create')" class="btn btn-success btn-sm px-3">
                            Новый приход
                        </Link>
                    </div>
                </div>
                <!-- /.card-header -->
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-bordered text-nowrap">
                            <thead>
                            <tr>
                                <th style="width: 10px">#</th>
                                <th>Номенклатура</th>
                                <th>Кол-во</th>
                                <th>Комментарий</th>
                                <th>Дата прихода</th>
                                <th>Дата создания</th>
                                <th width="40"></th>
                            </tr>
                            </thead>
                            <tbody>
                            <tr v-for="(nomenclatureArrival, index) in nomenclatureArrivals.data">
                                <td :data-id="nomenclatureArrival.id">{{ ((nomenclatureArrivals.current_page - 1) * nomenclatureArrivals.per_page) + index + 1 }}</td>
                                <td>{{nomenclatureArrival.nomenclature}}</td>
                                <td>{{nomenclatureArrival.quantity}} {{nomenclatureArrival.unit}}</td>
                                <td>{{nomenclatureArrival.comment}}</td>
                                <td>{{nomenclatureArrival.arrival_at}}</td>
                                <td>{{nomenclatureArrival.created_at}}</td>
                                <td class="text-center">
                                    <Link v-if="nomenclatureArrival.can_edit"
                                          class="btn btn-sm btn-outline-primary mr-2"
                                          :href="route('nomenclature-arrivals.edit', nomenclatureArrival.id)">
                                        <i class="fa fa-pencil-alt"></i>
                                    </Link>

                                    <delete-btn
                                        v-if="nomenclatureArrival.can_edit"
                                        :url="route('nomenclature-arrivals.destroy', nomenclatureArrival.id)"
                                    />
                                </td>
                            </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
                <!-- /.card-body -->

                <div class="card-footer clearfix" v-if="nomenclatureArrivals.links.length > 3">
                    <pagination :links="nomenclatureArrivals.links"/>
                </div>
            </div>
        </div>
    </div>
</template>
<script>
import {Head, Link} from "@inertiajs/inertia-vue3";
import Pagination from "@/Shared/Pagination.vue";
import DeleteBtn from "@/Shared/DeleteBtn.vue";

export default {
    components: {DeleteBtn, Pagination, Head, Link},
    props: ['nomenclatureArrivals'],
}
</script>
