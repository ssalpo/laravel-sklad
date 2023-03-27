<template>
    <Head>
        <title>Аналитика</title>
    </Head>

    <div class="content-header">
        <div class="container">
            <h1 class="m-0">Аналитика</h1>
        </div>
    </div>

    <div class="content">
        <div class="container">
            <div class="card">
                <div class="card-body">
                    <h5 class="mb-3">Остаток на складе</h5>
                    <div class="table-responsive">
                        <table class="table table-bordered">
                            <thead>
                            <tr>
                                <th width="50%">Номенклатура</th>
                                <th>Остаток</th>
                            </tr>
                            </thead>
                            <tbody>
                            <tr>
                                <td>Товар 1</td>
                                <td>10 шт.</td>
                            </tr>
                            <tr>
                                <td>Товар 2</td>
                                <td>20 шт.</td>
                            </tr>
                            </tbody>
                        </table>
                    </div>

                    <h5 class="mb-3 mt-4">Прибыль</h5>
                    <div class="table-responsive">
                        <table class="table table-bordered">
                            <thead>
                            <tr>
                                <th width="50%">За сегодня</th>
                                <th>За месяц</th>
                            </tr>
                            </thead>
                            <tbody>
                            <tr>
                                <td>5 000</td>
                                <td>30 000</td>
                            </tr>
                            </tbody>
                        </table>
                    </div>

                    <h5 class="mb-3 mt-4">Продажи товаров за сегодня</h5>
                    <div class="table-responsive">
                        <table class="table table-bordered">
                            <thead>
                            <tr>
                                <th width="50%">Номенклатура</th>
                                <th>Общая сумма продажи</th>
                                <th>Прибыль</th>
                            </tr>
                            </thead>
                            <tbody>
                            <tr>
                                <td>Товар 1</td>
                                <td>16500 сом.</td>
                                <td>2 000 сом.</td>
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
    components: {Head, Link}
}
</script>
