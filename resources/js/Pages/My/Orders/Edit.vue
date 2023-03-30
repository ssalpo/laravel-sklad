<template>
    <Head>
        <title>{{order?.id ? 'Обновление данных заявки' : 'Новая заявка'}}</title>
    </Head>

    <div class="content-header">
        <div class="container">
            <h5 class="m-0">{{ order?.id ? 'Обновление данных заявки' : 'Новая заявка' }}</h5>
        </div>
    </div>

    <div class="content">
        <div class="container">
            <div class="card card-primary">
                <!-- form start -->
                <form @submit.prevent="submit">
                    <div class="card-body">
                        <div class="form-group">
                            <label class="form-asterisk">Клиент</label>
                            <select class="form-control"
                                    :class="{'is-invalid': errors.client_id}"
                                    v-model.trim="form.client_id">
                                <option :value="client.id"
                                        v-for="client in clients">{{ client.name }}
                                </option>
                            </select>

                            <div v-if="errors.client_id" class="error invalid-feedback">
                                {{ errors.client_id }}
                            </div>
                        </div>

                        <div class="row mb-1" v-for="(orderItem, index) in form.orderItems">
                            <div class="col-6 col-sm-6">
                                <select class="form-control form-control-sm" v-model.trim="orderItem.nomenclature_id">
                                    <option :value="nomenclature.id"
                                            :disabled="selectedNomenclatures.includes(nomenclature.id)"
                                            v-for="nomenclature in nomenclatures">{{ nomenclature.name }}
                                    </option>
                                </select>
                            </div>
                            <div class="col-4 col-sm-4">
                                <input type="text" class="form-control form-control-sm"
                                       v-model.trim="orderItem.quantity">
                            </div>
                            <div class="col-2">
                                <button type="button" class="btn btn-sm btn-danger" @click="removeOrderItem(index)">
                                    <i class="fa fa-trash"></i>
                                </button>
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
                                Итого: {{ totalSum }} сом.
                            </div>
                        </div>
                    </div>
                    <!-- /.card-body -->

                    <div class="card-footer">
                        <button type="submit" :disabled="form.processing" class="btn btn-primary">
                            <span v-if="form.processing">
                                <i class="fas fa-spinner fa-spin"></i> Сохранение...
                            </span>
                            <span v-else>{{ order?.id ? 'Сохранить' : 'Добавить' }}</span>
                        </button>

                        <Link :href="route('orders.index')" :class="{disabled: form.processing}"
                              class="btn btn-default ml-2">Отменить
                        </Link>
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

export default {
    props: ['order', 'clients', 'nomenclatures', 'errors'],
    components: {Head, Link},
    data() {
        return {
            form: useForm({
                client_id: this.order?.client_id,
                orderItems: this.order?.orderItems || [{nomenclature_id: null, quantity: null}]
            }),
        }
    },
    computed: {
        totalSum() {
            return this.form.orderItems.reduce((a, e) => {
                if (!e.nomenclature_id) return a;

                let nomenclature = this.groupedNomenclatures[e.nomenclature_id];

                return a + (nomenclature.price_for_sale * parseInt(e.quantity) || 0)
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
        submit() {
            if (!this.order?.id) {
                this.form.post(route('my.orders.store'));
                return;
            }

            this.form.put(route('my.orders.update', this.order.id))
        },
        addOrderItem() {
            if (!this.canAddNomenclature) return;

            this.form.orderItems.push({nomenclature_id: null, quantity: null})
        },
        removeOrderItem(index) {
            this.form.orderItems.splice(index, 1)
        }
    },
    watch: {
        ['form.order_items']: {
            deep: true,
            handler() {
            }
        }
    }
}
</script>