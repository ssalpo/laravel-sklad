<template>
    <Head>
        <title>Мои заявки</title>
    </Head>

    <div class="content-header">
        <div class="container">
            <h5 class="m-0">Мои заявки</h5>
        </div>
    </div>

    <div class="content">
        <div class="container">
            <div class="card">
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-bordered">
                            <thead>
                            <tr>
                                <th style="width: 10px">#</th>
                                <th>Клиент</th>
                                <th>Сумма</th>
                                <th>Статус</th>
                                <th width="100">Действия</th>
                            </tr>
                            </thead>
                            <tbody>
                            <tr v-for="order in orders.data">
                                <td>{{order.id}}</td>
                                <td><Link :href="route('my.orders.show', order.id)">{{order.client}}</Link></td>
                                <td>{{order.amount}} сом.</td>
                                <td>{{$page.props.shared.orderStatusLabels[order.status]}}</td>
                                <td class="text-center">
                                    <Link v-if="order.status === 1" method="delete" as="button"
                                          type="button"
                                          preserve-sscroll
                                          preserve-state
                                          :href="route('my.orders.destroy', order.id)"
                                          class="btn btn-sm btn-link">
                                        <i class="fa fa-trash-alt text-danger"></i>
                                    </Link>
                                </td>
                            </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
                <!-- /.card-body -->

                <div class="card-footer clearfix" v-if="orders.links.length > 3">
                    <pagination :links="orders.links"/>
                </div>
            </div>
        </div>
    </div>
</template>
<script>
import {Head, Link} from "@inertiajs/inertia-vue3";
import Pagination from "../../../Shared/Pagination.vue";

export default {
    components: {Pagination, Head, Link},
    props: ['orders'],
}
</script>
