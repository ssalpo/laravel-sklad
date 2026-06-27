<template>
    <Head>
        <title>{{order?.id ? 'Обновление данных заявки' : 'Новая заявка'}}</title>
    </Head>

    <div class="content-header">
        <div class="container-fluid">
            <h5 class="m-0">{{ order?.id ? 'Обновление данных заявки' : 'Новая заявка' }}</h5>
        </div>
    </div>

    <div class="content">
        <div class="container-fluid">
            <div class="card card-primary">

                <form @submit.prevent="submit">
                    <div class="card-body">
                        <div class="mx-auto col col-md-6">

                            <div class="form-group">
                                <label class="form-asterisk">Клиент</label>

                                <select2-clients
                                    :prefetch="false"
                                    :options="clients"
                                    class="form-control-sm"
                                    :is-invalid="errors.client_id !== undefined"
                                    v-model.number="form.client_id"
                                />

                                <div v-if="errors.client_id" class="error invalid-feedback">
                                    {{ errors.client_id }}
                                </div>
                            </div>

                            <div class="row">
                                <div class="col">
                                    <label class="form-asterisk">Товары</label>
                                </div>
                            </div>

                            <div class="row nomenclatures-line" :class="{'mb-2': form.orderItems.length > index + 1}"
                                 v-for="(orderItem, index) in form.orderItems">
                                <div class="col-12">
                                    <div class="row">
                                        <div class="col-10">
                                            <div class="row mb-2">
                                                <div class="col-12">
                                                    <select2-nomenclatures
                                                        :prefetch="false"
                                                        :options="nomenclatures"
                                                        :key="index"
                                                        class="form-control-sm"
                                                        :disabledValues="selectedNomenclatures"
                                                        :is-invalid="errors['orderItems.' + index + '.nomenclature_id'] !== undefined"
                                                        :model-value="orderItem.nomenclature_id"
                                                        @update:modelValue="selectOrderItemNomenclature(orderItem, $event)"
                                                    />
                                                </div>
                                            </div>

                                            <div class="row">
                                                <div class="col-6">
                                                    <numeric-field
                                                        only-integer
                                                        placeholder="Кол-во"
                                                        :class="{'is-invalid': errors['orderItems.' + index + '.quantity']}"
                                                        class="form-control form-control-sm"
                                                        v-model="orderItem.quantity"/>
                                                </div>

                                                <div class="col-6">
                                                    <numeric-field
                                                        :precision="2"
                                                        placeholder="Сумма"
                                                        :class="{'is-invalid': errors['orderItems.' + index + '.price_for_sale']}"
                                                        class="form-control form-control-sm"
                                                        v-model="orderItem.price_for_sale"/>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-2 align-content-center">
                                            <button type="button" class="btn btn-sm btn-danger"
                                                    @click="removeOrderItem(index)">
                                                <i class="fa fa-trash"></i>
                                            </button>
                                        </div>
                                    </div>

                                    <div class="row">
                                        <div class="col-12"
                                             v-if="errors['orderItems.' + index + '.nomenclature_id'] || errors['orderItems.' + index + '.quantity']">
                                            <div class="error invalid-feedback" style="display: block !important;">
                                                Заполните корректно поля
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="row"
                                 v-if="errors['orderItems']">
                                <div class="col">
                                    <div class="error invalid-feedback" style="display: block !important;">
                                        Необходимо добавить товар
                                    </div>
                                </div>
                            </div>

                            <div class="row mt-3">
                                <div class="col-12 col-sm-6">
                                    <button v-if="this.canAddNomenclature" type="button" class="btn btn-sm btn-link"
                                            @click="addOrderItem">
                                        <i class="fa fa-plus-circle"></i>
                                        Добавить товар
                                    </button>
                                </div>
                                <div class="col-12 col-sm-6 mt-3 mt-sm-0" v-if="this.form.orderItems.length > 0">
                                    Итого: {{ numberFormat(totalSum, 2) }} сом.
                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- /.card-body -->

                    <div class="card-footer">
                        <div class="mx-auto col col-md-6 text-right">
                            <button type="submit" :disabled="form.processing" class="btn btn-primary">
                            <span v-if="form.processing">
                                <i class="fas fa-spinner fa-spin"></i> Сохранение...
                            </span>
                                <span v-else>{{ order?.id ? 'Сохранить' : 'Добавить' }}</span>
                            </button>

                            <Link :href="route('my.orders.index')" :class="{disabled: form.processing}"
                                  class="btn btn-default ml-2">Отменить
                            </Link>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</template>
<script>
import {Head, Link, useForm} from "@inertiajs/inertia-vue3";
import map from "lodash/map";
import compact from "lodash/compact";
import keyBy from "lodash/keyBy";
import get from "lodash/get";
import find from "lodash/find";
import {numberFormat} from "@/functions";
import Select2 from "@/Shared/Select2.vue";
import NumericField from "@/Shared/NumericField.vue";
import Select2Nomenclatures from "@/Shared/Select2Nomenclatures.vue";
import Select2Clients from "@/Shared/Select2Clients.vue";

export default {
    props: ['order', 'clients', 'selectedClientId', 'nomenclatures', 'errors'],
    components: {Select2Clients, Select2Nomenclatures, Select2, NumericField, Head, Link},
    data() {
        return {
            selectedClient: null,
            form: useForm({
                client_id: this.order?.client_id || parseInt(this.selectedClientId),
                orderItems: this.order?.orderItems || [{nomenclature_id: null, quantity: null, price_for_sale: null}]
            }),
        }
    },
    computed: {
        totalSum() {
            return this.form.orderItems.reduce((a, e) => {
                if (!e.nomenclature_id) return a;

                return a + (e.price_for_sale * parseInt(e.quantity) || 0);
            }, 0)
        },
        groupedNomenclatures() {
            return keyBy(this.nomenclatures, 'id')
        },
        selectedNomenclatures() {
            return compact(map(this.form.orderItems, 'nomenclature_id'))
        },
        canAddNomenclature() {
            return this.nomenclatures.length > this.form.orderItems.length;
        }
    },
    methods: {
        numberFormat,
        submit() {
            if (!this.order?.id) {
                this.form.post(route('orders.store'));
                return;
            }

            this.form.put(route('orders.update', this.order.id))
        },
        addOrderItem() {
            if (!this.canAddNomenclature) return;

            this.form.orderItems.push({nomenclature_id: null, quantity: null, price_for_sale: null})
        },
        removeOrderItem(index) {
            this.form.orderItems.splice(index, 1)
        },
        selectOrderItemNomenclature(orderItem, nomenclatureId) {
            let id = parseInt(nomenclatureId);

            orderItem.nomenclature_id = isNaN(id) ? null : id;
            this.syncOrderItemPrice(orderItem);
        },
        syncOrderItemPrice(orderItem) {
            if (!orderItem.nomenclature_id) {
                orderItem.price_for_sale = null;

                return;
            }

            orderItem.price_for_sale = this.getOrderItemPrice(orderItem.nomenclature_id);
        },
        syncOrderItemPrices() {
            this.form.orderItems.forEach((orderItem) => this.syncOrderItemPrice(orderItem));
        },
        getOrderItemPrice(nomenclatureId) {
            let nomenclature = this.groupedNomenclatures[nomenclatureId];

            if (!nomenclature) return null;

            if (this.selectedClient?.id) {
                let templatePrice = get(this.selectedClient.priceTemplates, nomenclatureId, null);

                if (templatePrice !== null) {
                    return templatePrice;
                }

                return nomenclature.price_for_sale - get(this.selectedClient.discounts, nomenclatureId, 0);
            }

            return nomenclature.price_for_sale;
        }
    },
    watch: {
        ['form.client_id'](id) {
            this.selectedClient = find(this.clients, ['id', id])
            this.syncOrderItemPrices();
        }
    }
}
</script>
