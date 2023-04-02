<template>
    <Head>
        <title>Долги клиентов</title>
    </Head>

    <div class="content-header">
        <div class="container">
            <h1 class="m-0">Долги клиентов</h1>
        </div>
    </div>

    <div class="content">
        <div class="container">
            <div class="card">
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-bordered  text-nowrap">
                            <thead>
                            <tr>
                                <th style="width: 10px">#</th>
                                <th>Имя</th>
                                <th>Общий долг</th>
                            </tr>
                            </thead>
                            <tbody>
                            <tr v-for="debt in debts">
                                <td>{{debt.client_id}}</td>
                                <td>
                                    <Link :href="route('client-debts.show', debt.client_id)">{{debt.client_name}}</Link>
                                </td>
                                <td>{{numberFormat(debt.totalAmount)}} сом.</td>
                            </tr>
                            </tbody>
                        </table>
                    </div>
                </div>

            </div>
        </div>
    </div>
</template>
<script>
import {Head, Link} from "@inertiajs/inertia-vue3";

export default {
    components: {Head, Link},
    props: ['debts'],
}
</script>
