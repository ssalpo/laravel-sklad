<template>
    <div>
        <h3 class="page-title">Накладная №{{ order.id }}</h3>

        <p class="pt-2" style="font-size: 18px;">
            Покупатель: {{ order.client }}
        </p>

        <table class="table table-bordered">
            <thead>
            <tr>
                <th class="text-center" width="50">№</th>
                <th>Номенклатура</th>
                <th class="text-center">Кол-во</th>
                <th class="text-center">Цена за единицу</th>
                <th class="text-right">Сумма продажи</th>
            </tr>
            </thead>
            <tbody>
            <tr v-for="(orderItem, index) in orderItems">
                <td class="text-center">{{ index + 1 }}</td>
                <td>{{ orderItem.nomenclature }}</td>
                <td class="text-center">{{ orderItem.quantity }} {{ $page.props.shared.unitLabels[orderItem.unit] }}</td>
                <td class="text-center">{{ numberFormat(orderItem.price_for_sale) }} сом.</td>
                <td class="text-right">{{ numberFormat(orderItem.price_for_sale * orderItem.quantity) }} сом.</td>
            </tr>
            <tr>
                <td colspan="4"></td>
                <td class="text-right">
                    <b>Итого:</b> {{ numberFormat(order.amount) }} сом.
                </td>
            </tr>
            </tbody>
        </table>

        <div class="sign-block mt-5">
            <div class="row">
                <div class="col-5 offset-1">
                    <table width="90%">
                        <tr>
                            <td width="75">Отпустил</td>
                            <td style="border-bottom: 1px solid #000"></td>
                        </tr>
                        <tr>
                            <td></td>
                            <td class="text-center">подпись</td>
                        </tr>
                    </table>
                </div>

                <div></div>

                <div class="col-5">
                    <table width="90%">
                        <tr>
                            <td width="75">Принял</td>
                            <td style="border-bottom: 1px solid #000"></td>
                        </tr>
                        <tr>
                            <td></td>
                            <td class="text-center">подпись</td>
                        </tr>
                    </table>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
export default {
    props: ['order', 'orderItems']
}
</script>

<style scoped>
.page-title {
    border-bottom: 4px solid #000;
    padding-bottom: 8px;
}

.table-bordered td, .table-bordered th {
    padding: 5px 15px;
}

.table thead th, .table tbody td {
    border-color: #000;
}

@media print {
    .table thead th, .table tbody td {
        border-color: #000 !important;
    }
}
</style>
