<template>
    <Head>
        <title>Категории</title>
    </Head>

    <div class="content-header">
        <div class="container">
            <h1 class="m-0">Категории</h1>
        </div>
    </div>

    <div class="content">
        <div class="container">
            <div class="card">
                <div class="card-header">
                    <div class="card-tools">
                        <Link :href="route('categories.create')" class="btn btn-success btn-sm px-3">
                            Новая категория
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
                            <tr v-for="(category, index) in categories.data">
                                <td :data-id="category.id">{{ ((categories.current_page - 1) * categories.per_page) + index + 1 }}</td>
                                <td>{{category.name}}</td>
                                <td class="text-center">
                                    <Link :href="route('categories.edit', category.id)">
                                        <i class="fa fa-pencil-alt"></i>
                                    </Link>
                                </td>
                            </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
                <!-- /.card-body -->

                <div class="card-footer clearfix" v-if="categories.links.length > 3">
                    <pagination :links="categories.links"/>
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
    props: ['categories'],
}
</script>
