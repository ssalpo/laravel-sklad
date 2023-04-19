<template>
    <Head>
        <title>Списание</title>
    </Head>

    <div class="content-header">
        <div class="container-fluid">
            <h5 class="m-0">Списание</h5>
        </div>
    </div>

    <div class="content">
        <div class="container-fluid">
            <div class="card">
                <div class="card-header">
                    <div class="card-tools">
                        <Link :href="route('nomenclature-operations.create', {type: 1})" class="btn btn-success btn-sm px-3">
                            Новое списание
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
                                <th>Дата создания</th>
                                <th width="40"></th>
                            </tr>
                            </thead>
                            <tbody>
                            <tr v-for="(nomenclatureOperation, index) in nomenclatureOperations.data">
                                <td>{{ nomenclatureOperation.id }}</td>
                                <td>{{nomenclatureOperation.nomenclature.name}}</td>
                                <td>{{nomenclatureOperation.quantity}} {{nomenclatureOperation.nomenclature.unit}}</td>
                                <td>{{nomenclatureOperation.created_at}}</td>
                                <td class="text-center">
                                    <Link class="btn btn-sm btn-outline-primary mr-1"
                                          v-if="nomenclatureOperation.can_edit"
                                          :href="route('nomenclature-operations.edit', {nomenclature_operation: nomenclatureOperation.id, type: 1})">
                                        <i class="fa fa-pencil-alt"></i>
                                    </Link>

                                    <delete-btn
                                        v-if="nomenclatureOperation.can_edit"
                                        :url="route('nomenclature-operations.destroy', {nomenclature_operation: nomenclatureOperation.id, type: currentType})"
                                    />
                                </td>
                            </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
                <!-- /.card-body -->

                <div class="card-footer clearfix" v-if="nomenclatureOperations.links.length > 3">
                    <pagination :links="nomenclatureOperations.links"/>
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
    props: ['backRoute', 'currentType', 'nomenclatureOperations'],
    data(){
        return {
            typeLabels: {
                1: {
                    title: 'Списание',
                    createBtn: 'Новое списание'
                }
            }
        }
    }
}
</script>
