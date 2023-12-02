<template>
    <Head>
        <title>{{rawMaterial?.id ? 'Обновление данных' : 'Новая покупка'}}</title>
    </Head>

    <div class="content-header">
        <div class="container-fluid">
            <h1 class="m-0">{{ rawMaterial?.id ? 'Обновление данных' : 'Новый клиент' }}</h1>
        </div>
    </div>

    <div class="content">
        <div class="container-fluid">
            <div class="card card-primary">
                <!-- form start -->
                <form @submit.prevent="submit">
                    <div class="card-body">
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
                            <numeric-field class="form-control"
                                           :class="{'is-invalid': errors.quantity}"
                                           v-model="form.quantity" />
                            <div v-if="errors.quantity" class="error invalid-feedback">
                                {{ errors.quantity }}
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="form-asterisk">Стоимость</label>
                            <numeric-field :precision="2" class="form-control"
                                           :class="{'is-invalid': errors.price}"
                                           v-model="form.price" />
                            <div v-if="errors.price" class="error invalid-feedback">
                                {{ errors.price }}
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="form-asterisk">Общая стоимость покупки</label>
                            <numeric-field :precision="2" class="form-control"
                                           :class="{'is-invalid': errors.total_amount}"
                                           v-model="form.total_amount" />
                            <div v-if="errors.total_amount" class="error invalid-feedback">
                                {{ errors.total_amount }}
                            </div>
                        </div>

                        <div class="form-group" v-if="!rawMaterial?.id">
                            <label class="form-asterisk">Сумма предоплаты</label>
                            <numeric-field :precision="2" class="form-control"
                                           :class="{'is-invalid': errors.prepaid_amount}"
                                           v-model="form.prepaid_amount" />
                            <div v-if="errors.prepaid_amount" class="error invalid-feedback">
                                {{ errors.prepaid_amount }}
                            </div>
                        </div>
                    </div>
                    <!-- /.card-body -->

                    <div class="card-footer">
                        <button type="submit" :disabled="form.processing" class="btn btn-primary">
                            <span v-if="form.processing">
                                <i class="fas fa-spinner fa-spin"></i> Сохранение...
                            </span>
                            <span v-else>{{ rawMaterial?.id ? 'Сохранить' : 'Добавить' }}</span>
                        </button>

                        <Link :href="route('raw-materials.index')" :class="{disabled: form.processing}" class="btn btn-default ml-2">Отменить</Link>
                    </div>
                </form>
            </div>
        </div>
    </div>
</template>
<script>
import {Head, Link, useForm} from "@inertiajs/inertia-vue3";
import CustomSelect from "../../Shared/CustomSelect.vue";
import { vMaska } from "maska";
import NumericField from "../../Shared/NumericField.vue";

export default {
    props: ['rawMaterial', 'nomenclatures', 'errors'],
    components: {NumericField, CustomSelect, Head, Link},
    directives: { maska: vMaska },
    data() {
        return {
            form: useForm({
                nomenclature_id: this.rawMaterial?.nomenclature_id,
                quantity: this.rawMaterial?.quantity || 0,
                price: this.rawMaterial?.price || 0,
                total_amount: this.rawMaterial?.total_amount || 0,
                prepaid_amount: this.rawMaterial?.prepaid_amount || 0,
            }),
        }
    },
    methods: {
        submit() {
            if (!this.rawMaterial?.id) {
                this.form.post(route('raw-materials.store'));
                return;
            }

            this.form.put(route('raw-materials.update', this.rawMaterial.id))
        }
    }
}
</script>
