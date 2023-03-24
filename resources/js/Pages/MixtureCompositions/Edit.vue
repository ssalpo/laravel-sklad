<template>
    <Head>
        <title>{{mixtureComposition?.id ? 'Обновление данных' : 'Новый состав'}}</title>
    </Head>

    <div class="content-header">
        <div class="container">
            <h1 class="m-0">{{ mixtureComposition?.id ? 'Обновление данных' : 'Новый состав' }}</h1>
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
                                <option :value="nomenclature.id"
                                        v-for="nomenclature in nomenclatures">{{ nomenclature.name }}
                                </option>
                            </select>

                            <div v-if="errors.nomenclature_id" class="error invalid-feedback">
                                {{ errors.nomenclature_id }}
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
                            <label class="form-asterisk">Масса продукта</label>
                            <input type="text" class="form-control"
                                   :class="{'is-invalid': errors.weight}"
                                   v-model.number="form.weight">
                            <div v-if="errors.weight" class="error invalid-feedback">
                                {{ errors.weight }}
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="form-asterisk">Единица измерения для массы продукта</label>
                            <select class="form-control"
                                    :class="{'is-invalid': errors.weight_unit_id}"
                                    v-model.trim="form.weight_unit_id">
                                <option :value="unit.id" v-for="unit in units">{{unit.name}}</option>
                            </select>

                            <div v-if="errors.weight_unit_id" class="error invalid-feedback">
                                {{ errors.weight_unit_id }}
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="form-asterisk">Кол-во воды</label>
                            <input type="text" class="form-control"
                                   :class="{'is-invalid': errors.water}"
                                   v-model.number="form.water">
                            <div v-if="errors.water" class="error invalid-feedback">
                                {{ errors.water }}
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="form-asterisk">Единица измерения для массы воды</label>
                            <select class="form-control"
                                    :class="{'is-invalid': errors.water_unit_id}"
                                    v-model.trim="form.water_unit_id">
                                <option :value="unit.id" v-for="unit in units">{{unit.name}}</option>
                            </select>

                            <div v-if="errors.water_unit_id" class="error invalid-feedback">
                                {{ errors.water_unit_id }}
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="form-asterisk">Сумма оплаты работника</label>
                            <input type="text" class="form-control"
                                   :class="{'is-invalid': errors.worker_price}"
                                   v-model.number="form.worker_price">
                            <div v-if="errors.worker_price" class="error invalid-feedback">
                                {{ errors.worker_price }}
                            </div>
                        </div>
                    </div>
                    <!-- /.card-body -->

                    <div class="card-footer">
                        <button type="submit" :disabled="form.processing" class="btn btn-primary">
                            <span v-if="form.processing">
                                <i class="fas fa-spinner fa-spin"></i> Сохранение...
                            </span>
                            <span v-else>{{ mixtureComposition?.id ? 'Сохранить' : 'Добавить' }}</span>
                        </button>

                        <Link :href="route('mixture-compositions.index')" :class="{disabled: form.processing}" class="btn btn-default ml-2">Отменить</Link>
                    </div>
                </form>
            </div>
        </div>
    </div>
</template>
<script>
import {Head, Link, useForm} from "@inertiajs/inertia-vue3";

export default {
    props: ['mixtureComposition', 'units', 'nomenclatures', 'errors'],
    components: {Head, Link},
    data() {
        return {
            form: useForm({
                nomenclature_id: this.mixtureComposition?.nomenclature_id,
                currency_type: this.mixtureComposition?.currency_type,
                weight: this.mixtureComposition?.weight,
                weight_unit_id: this.mixtureComposition?.weight_unit_id,
                water: this.mixtureComposition?.water,
                water_unit_id: this.mixtureComposition?.water_unit_id,
                worker_price: this.mixtureComposition?.worker_price,
            }),
        }
    },
    methods: {
        submit() {
            if (!this.mixtureComposition?.id) {
                this.form.post(route('mixture-compositions.store'));
                return;
            }

            this.form.put(route('mixture-compositions.update', this.mixtureComposition.id))
        }
    }
}
</script>
