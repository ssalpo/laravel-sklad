<template>
    <Head>
        <title>Списание</title>
    </Head>

    <div class="content-header">
        <div class="container">
            <h5 class="m-0">Списание</h5>
        </div>
    </div>

    <div class="content">
        <div class="container">
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
                                <th width="40"></th>
                            </tr>
                            </thead>
                            <tbody>
                            <tr v-for="(nomenclatureOperation, index) in nomenclatureOperations.data">
                                <td :data-id="nomenclatureOperation.id">{{ ((nomenclatureOperations.current_page - 1) * nomenclatureOperations.per_page) + index + 1 }}</td>
                                <td>{{nomenclatureOperation.nomenclature.name}}</td>
                                <td>{{nomenclatureOperation.quantity}} {{nomenclatureOperation.nomenclature.unit}}</td>
                                <td class="text-center">
                                    <Link class="btn btn-sm btn-outline-primary mr-1" :href="route('nomenclature-operations.edit', {nomenclature_operation: nomenclatureOperation.id, type: 1})">
                                        <i class="fa fa-pencil-alt"></i>
                                    </Link>

                                    <Link method="delete" as="button"
                                          type="button"
                                          preserve-sscroll
                                          preserve-state
                                          :href="route('nomenclature-operations.destroy', {nomenclature_operation: nomenclatureOperation.id, type: currentType})"
                                          class="btn btn-sm btn-outline-danger">
                                        <i class="fa fa-trash-alt"></i>
                                    </Link>
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
import Pagination from "../../Shared/Pagination.vue";

export default {
    components: {Pagination, Head, Link},
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
