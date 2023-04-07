<template>
    <Head>
        <title>{{mixtureCompositionItem?.id ? 'Обновление данных' : 'Новый состав'}}</title>
    </Head>

    <div class="content-header">
        <div class="container">
            <h1 class="m-0">{{ mixtureCompositionItem?.id ? 'Обновление данных' : 'Новый состав' }}</h1>
        </div>
    </div>

    <div class="content">
        <div class="container">
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
                                <label class="form-asterisk">Количество</label>
                                <numeric-field type="text" class="form-control"
                                       :class="{'is-invalid': errors.quantity}"
                                       v-model.number="form.quantity" />
                                <div v-if="errors.quantity" class="error invalid-feedback">
                                    {{ errors.quantity }}
                                </div>
                            </div>

                            <div class="form-group">
                                <label class="form-asterisk">Единица измерения количества</label>

                                <select class="form-control"
                                        disabled
                                        :class="{'is-invalid': errors.unit}"
                                        v-model.trim="form.unit">
                                    <option :value="index" v-for="(label, index) in $page.props.shared.unitLabels">
                                        {{ label }}
                                    </option>
                                </select>

                                <div v-if="errors.unit" class="error invalid-feedback">
                                    {{ errors.unit }}
                                </div>
                            </div>

                            <div class="form-group">
                                <label class="form-asterisk">Сумма</label>
                                <numeric-field :precision="4" type="text" class="form-control"
                                       :class="{'is-invalid': errors.price}"
                                       v-model.number="form.price" />
                                <div v-if="errors.price" class="error invalid-feedback">
                                    {{ errors.price }}
                                </div>
                            </div>

                            <div class="form-group">
                                <div class="custom-control custom-checkbox">
                                    <input class="custom-control-input" id="endResult" v-model="form.end_result"
                                           type="checkbox"/>

                                    <label for="endResult" class="custom-control-label">
                                        Добавить в конечный расчет?
                                    </label>
                                </div>

                                <div v-if="errors.end_result" class="invalid-feedback-simple">
                                    {{ errors.end_result }}
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
                                <span v-else>{{ mixtureCompositionItem?.id ? 'Сохранить' : 'Добавить' }}</span>
                            </button>

                            <Link :href="route('mixture-compositions.show', this.mixtureCompositionId)"
                                  :class="{disabled: form.processing}" class="btn btn-default ml-2">Отменить
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
import get from "lodash/get";
import find from "lodash/find";
import CustomSelect from "../../Shared/CustomSelect.vue";
import NumericField from "../../Shared/NumericField.vue";

export default {
    props: ['mixtureCompositionId', 'mixtureCompositionItem', 'units', 'nomenclatures', 'errors'],
    components: {NumericField, CustomSelect, Head, Link},
    data() {
        return {
            form: useForm({
                nomenclature_id: this.mixtureCompositionItem?.nomenclature_id,
                price: this.mixtureCompositionItem?.price,
                quantity: this.mixtureCompositionItem?.quantity,
                unit: this.mixtureCompositionItem?.unit,
                end_result: this.mixtureCompositionItem?.end_result,
            }),
        }
    },
    methods: {
        submit() {
            if (!this.mixtureCompositionItem?.id) {
                this.form.post(route('mixture-composition-items.store', this.mixtureCompositionId));
                return;
            }

            this.form.put(route('mixture-composition-items.update', {
                'mixture_composition': this.mixtureCompositionId,
                'mixture_composition_item': this.mixtureCompositionItem.id
            }))
        }
    },
    watch: {
        ['form.nomenclature_id'](value) {
            this.form.unit = get(find(this.nomenclatures, {'id': value}), 'unit');
        }
    }
}
</script>
