<template>
    <Head>
        <title>{{nomenclature?.id ? 'Обновление данных номенклатуры' : 'Новая номенклатура'}}</title>
    </Head>

    <div class="content-header">
        <div class="container-fluid">
            <h1 class="m-0">{{ nomenclature?.id ? 'Обновление данных номенклатуры' : 'Новая номенклатура' }}</h1>
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
                                <label class="form-asterisk">Тип номенклатуры</label>

                                <select2-nomenclature-types
                                    v-model.number="form.type"
                                    :is-invalid="errors.type !== undefined"
                                />

                                <div v-if="errors.type" class="error invalid-feedback">
                                    {{ errors.type }}
                                </div>
                            </div>

                            <div class="form-group">
                                <label class="form-asterisk">Наименование</label>
                                <input type="text" class="form-control"
                                       :class="{'is-invalid': errors.name}"
                                       v-model.trim="form.name">

                                <div v-if="errors.name" class="error invalid-feedback">
                                    {{ errors.name }}
                                </div>
                            </div>

                            <div class="form-group">
                                <div class="custom-control custom-checkbox">
                                    <input class="custom-control-input" id="priceManual" v-model="form.is_price_manual"
                                           type="checkbox"/>

                                    <label for="priceManual" class="custom-control-label">
                                        Указать себестоимость вручную?
                                    </label>
                                </div>

                                <div v-if="errors.is_price_manual" class="invalid-feedback-simple">
                                    {{ errors.is_price_manual }}
                                </div>
                            </div>

                            <div class="form-group" v-if="form.is_price_manual">
                                <label class="form-asterisk">Себестоимость</label>
                                <numeric-field :precision="4" class="form-control"
                                               :class="{'is-invalid': errors.price}"
                                               v-model="form.price" />
                                <div v-if="errors.price" class="error invalid-feedback">
                                    {{ errors.price }}
                                </div>
                            </div>

                            <div class="form-group">
                                <label class="form-asterisk">Единица измерения</label>
                                <select class="form-control"
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
                        </div>
                    </div>
                    <!-- /.card-body -->

                    <div class="card-footer">
                        <div class="mx-auto col col-md-6 text-right">
                            <button type="submit" :disabled="form.processing" class="btn btn-primary">
                                <span v-if="form.processing">
                                    <i class="fas fa-spinner fa-spin"></i> Сохранение...
                                </span>
                                <span v-else>{{ nomenclature?.id ? 'Сохранить' : 'Добавить' }}</span>
                            </button>

                            <Link :href="route('nomenclatures.index')" :class="{disabled: form.processing}"
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
import NumericField from "../../Shared/NumericField.vue";
import Select2NomenclatureTypes from "../../Shared/Select2NomenclatureTypes.vue";

export default {
    props: ['nomenclature', 'units', 'categories', 'errors'],
    components: {Select2NomenclatureTypes, NumericField, Head, Link},
    data() {
        return {
            form: useForm({
                name: this.nomenclature?.name,
                category_id: this.nomenclature?.category_id,
                is_price_manual: this.nomenclature?.is_price_manual || false,
                price: this.nomenclature?.price || undefined,
                dollar_exchange_rate: this.nomenclature?.dollar_exchange_rate,
                price_for_sale: this.nomenclature?.price_for_sale,
                type: this.nomenclature?.type,
                unit: this.nomenclature?.unit || 7,
            }),
        }
    },
    methods: {
        submit() {
            if(!this.form.is_price_manual) {
                this.form.price = undefined;
            }

            if (!this.nomenclature?.id) {
                this.form.post(route('nomenclatures.store'));
                return;
            }

            this.form.put(route('nomenclatures.update', this.nomenclature.id))
        }
    }
}
</script>
