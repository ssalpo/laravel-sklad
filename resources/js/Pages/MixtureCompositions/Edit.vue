<template>
    <Head>
        <title>{{mixtureComposition?.id ? 'Обновление данных' : 'Новый состав'}}</title>
    </Head>

    <div class="content-header">
        <div class="container-fluid">
            <h1 class="m-0">{{ mixtureComposition?.id ? 'Обновление данных' : 'Новый состав' }}</h1>
        </div>
    </div>

    <div class="content">
        <div class="container-fluid">
            <div class="card card-primary">
                <!-- form start -->
                <form @submit.prevent="submit">
                    <div class="card-body">
                        <div class="mx-auto col col-md-6">
                            <div class="form-group">
                                <label class="form-asterisk">Номенклатура</label>

                                <custom-select
                                    full
                                    searchable
                                    :class="{'is-invalid': errors.nomenclature_id}"
                                    :options="nomenclatures"
                                    v-model.number="form.nomenclature_id"
                                    :value="form.nomenclature_id"
                                    label-key="name" />

                                <div v-if="errors.nomenclature_id" class="error invalid-feedback">
                                    {{ errors.nomenclature_id }}
                                </div>
                            </div>

                            <div class="form-group">
                                <label class="form-asterisk">Масса продукта</label>
                                <numeric-field class="form-control"
                                       :class="{'is-invalid': errors.weight}"
                                       v-model="form.weight" />
                                <div v-if="errors.weight" class="error invalid-feedback">
                                    {{ errors.weight }}
                                </div>
                            </div>

                            <div class="form-group">
                                <label class="form-asterisk">Единица измерения для массы продукта</label>
                                <select class="form-control"
                                        :class="{'is-invalid': errors.weight_unit}"
                                        v-model.trim="form.weight_unit">
                                    <option :value="index" v-for="(label, index) in $page.props.shared.unitLabels">{{ label }}</option>
                                </select>

                                <div v-if="errors.weight_unit" class="error invalid-feedback">
                                    {{ errors.weight_unit }}
                                </div>
                            </div>

                            <div class="form-group">
                                <label class="form-asterisk">Кол-во воды</label>
                                <numeric-field class="form-control"
                                       :class="{'is-invalid': errors.water}"
                                       v-model="form.water" />
                                <div v-if="errors.water" class="error invalid-feedback">
                                    {{ errors.water }}
                                </div>
                            </div>

                            <div class="form-group">
                                <label class="form-asterisk">Единица измерения для массы воды</label>
                                <select class="form-control"
                                        :class="{'is-invalid': errors.water_unit}"
                                        v-model.trim="form.water_unit">
                                    <option :value="index" v-for="(label, index) in $page.props.shared.unitLabels">{{ label }}</option>
                                </select>

                                <div v-if="errors.water_unit" class="error invalid-feedback">
                                    {{ errors.water_unit }}
                                </div>
                            </div>

                            <div class="form-group">
                                <label class="form-asterisk">Сумма оплаты работника</label>
                                <numeric-field :precision="4" class="form-control"
                                       :class="{'is-invalid': errors.worker_price}"
                                       v-model="form.worker_price" />
                                <div v-if="errors.worker_price" class="error invalid-feedback">
                                    {{ errors.worker_price }}
                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- /.card-body -->

                    <div class="card-footer">
                        <div class="mx-auto col col-md-6">
                            <button type="submit" :disabled="form.processing" class="btn btn-primary">
                                <span v-if="form.processing">
                                    <i class="fas fa-spinner fa-spin"></i> Сохранение...
                                </span>
                                <span v-else>{{ mixtureComposition?.id ? 'Сохранить' : 'Добавить' }}</span>
                            </button>

                            <Link :href="route('mixture-compositions.index')" :class="{disabled: form.processing}" class="btn btn-default ml-2">Отменить</Link>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</template>
<script>
import {Head, Link, useForm} from "@inertiajs/inertia-vue3";
import CustomSelect from "../../Shared/CustomSelect.vue";
import NumericField from "../../Shared/NumericField.vue";

export default {
    props: ['mixtureComposition', 'units', 'nomenclatures', 'errors'],
    components: {NumericField, CustomSelect, Head, Link},
    data() {
        return {
            form: useForm({
                nomenclature_id: this.mixtureComposition?.nomenclature_id,
                weight: this.mixtureComposition?.weight,
                weight_unit: this.mixtureComposition?.weight_unit,
                water: this.mixtureComposition?.water,
                water_unit: this.mixtureComposition?.water_unit,
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
