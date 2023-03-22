<template>
    <Head>
        <title>Ед. измерения</title>
    </Head>

    <div class="content-header">
        <div class="container">
            <h1 class="m-0">Ед. измерения</h1>
        </div>
    </div>

    <div class="content">
        <div class="container">
            <div class="card">
                <div class="card-header">
                    <div class="card-tools">
                        <Link :href="route('units.create')" class="btn btn-success btn-sm px-3">
                            Добавить
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
                                <th>Название</th>
                                <th width="40"></th>
                            </tr>
                            </thead>
                            <tbody>
                            <tr v-for="(unit, index) in units.data">
                                <td :data-id="unit.id">{{ ((units.current_page - 1) * units.per_page) + index + 1 }}</td>
                                <td>{{unit.name}}</td>
                                <td class="text-center">
                                    <Link :href="route('units.edit', unit.id)">
                                        <i class="fa fa-pencil-alt"></i>
                                    </Link>
                                </td>
                            </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
                <!-- /.card-body -->

                <div class="card-footer clearfix" v-if="units.links.length > 3">
                    <pagination :links="units.links"/>
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
    props: ['units'],
}
</script>
