<template>
    <Head>
        <title>{{nomenclatureArrival?.id ? 'Обновление данных' : 'Новый приход'}}</title>
    </Head>

    <div class="content-header">
        <div class="container">
            <h1 class="m-0">{{ nomenclatureArrival?.id ? 'Обновление данных' : 'Новый приход' }}</h1>
        </div>
    </div>

    <div class="content">
        <div class="container">
            <div class="card card-primary">
                <!-- form start -->
                <form @submit.prevent="submit">
                    <div class="card-body">
                        <div class="form-group">
                            <label class="form-asterisk">Номенклатура</label>
                            <select class="form-control"
                                    :class="{'is-invalid': errors.nomenclature_id}"
                                    v-model.trim="form.nomenclature_id">
                                <option @click="selectedNomenclature = nomenclature" :value="nomenclature.id"
                                        v-for="nomenclature in nomenclatures">{{ nomenclature.name }}
                                </option>
                            </select>

                            <div v-if="errors.nomenclature_id" class="error invalid-feedback">
                                {{ errors.nomenclature_id }}
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="form-asterisk">Количество</label>
                            <input type="text" class="form-control"
                                   :class="{'is-invalid': errors.quantity}"
                                   v-model.number="form.quantity">

                            <div v-if="errors.quantity" class="error invalid-feedback">
                                {{ errors.quantity }}
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="form-asterisk">Единица измерения</label>
                            <select disabled class="form-control"
                                    :class="{'is-invalid': errors.unit_id}"
                                    v-model.trim="form.unit_id">
                                <option :value="unit.id" v-for="unit in units">{{ unit.name }}</option>
                            </select>

                            <div v-if="errors.unit_id" class="error invalid-feedback">
                                {{ errors.unit_id }}
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="form-asterisk">Цена покупки</label>
                            <input type="text" class="form-control"
                                   :class="{'is-invalid': errors.price}"
                                   v-model.number="form.price">

                            <div v-if="errors.price" class="error invalid-feedback">
                                {{ errors.price }}
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="form-asterisk">Цена продажи</label>
                            <input type="text" class="form-control"
                                   :class="{'is-invalid': errors.price_for_sale}"
                                   v-model.number="form.price_for_sale">

                            <div v-if="errors.price_for_sale" class="error invalid-feedback">
                                {{ errors.price_for_sale }}
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="form-asterisk">Тип валюты</label>
                            <select class="form-control"
                                    :class="{'is-invalid': errors.currency_type}"
                                    v-model.number="form.currency_type">
                                <option :value="index"
                                        v-for="(currencyType, index) in $page.props.shared.currencyTypeLabels">
                                    {{ currencyType }}
                                </option>
                            </select>

                            <div v-if="errors.currency_type" class="error invalid-feedback">
                                {{ errors.currency_type }}
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="form-asterisk">Комментарий</label>
                            <input type="text" class="form-control"
                                   :class="{'is-invalid': errors.comment}"
                                   v-model.trim="form.comment">

                            <div v-if="errors.comment" class="error invalid-feedback">
                                {{ errors.comment }}
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="form-asterisk">Дата прихода</label>
                            <input type="datetime-local" class="form-control"
                                   :class="{'is-invalid': errors.arrival_at}"
                                   v-model="form.arrival_at">

                            <div v-if="errors.arrival_at" class="error invalid-feedback">
                                {{ errors.arrival_at }}
                            </div>
                        </div>
                    </div>
                    <!-- /.card-body -->

                    <div class="card-footer">
                        <button type="submit" :disabled="form.processing" class="btn btn-primary">
                            <span v-if="form.processing">
                                <i class="fas fa-spinner fa-spin"></i> Сохранение...
                            </span>
                            <span v-else>{{ nomenclatureArrival?.id ? 'Сохранить' : 'Добавить' }}</span>
                        </button>

                        <Link :href="route('nomenclature-arrivals.index')" :class="{disabled: form.processing}"
                              class="btn btn-default ml-2">Отменить
                        </Link>
                    </div>
                </form>
            </div>
        </div>
    </div>
</template>
<script>
import find from "lodash/find";
import get from "lodash/get";
import {Head, Link, useForm} from "@inertiajs/inertia-vue3";

export default {
    props: ['nomenclatureArrival', 'nomenclatures', 'units', 'currentDate', 'errors'],
    components: {Head, Link},
    data() {
        return {
            form: useForm({
                nomenclature_id: this.nomenclatureArrival?.nomenclature_id,
                quantity: this.nomenclatureArrival?.quantity,
                unit_id: this.nomenclatureArrival?.unit_id,
                price: this.nomenclatureArrival?.price,
                price_for_sale: this.nomenclatureArrival?.price_for_sale,
                currency_type: this.nomenclatureArrival?.currency_type,
                comment: this.nomenclatureArrival?.comment,
                arrival_at: this.nomenclatureArrival?.arrival_at || this.currentDate,
            }),
        }
    },
    methods: {
        submit() {
            if (!this.nomenclatureArrival?.id) {
                this.form.post(route('nomenclature-arrivals.store'));
                return;
            }

            this.form.put(route('nomenclature-arrivals.update', this.nomenclatureArrival.id))
        }
    },
    watch: {
        ['form.nomenclature_id'](value) {
            this.form.unit_id = get(find(this.units, 'id', value), 'id');
        }
    }
}
</script>
