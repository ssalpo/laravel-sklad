<template>
    <Head>
        <title>{{nomenclatureOperation?.id ? 'Обновление данных' : typeLabels[currentType]['title']}}</title>
    </Head>

    <div class="content-header">
        <div class="container-fluid">
            <h1 class="m-0">{{ nomenclatureOperation?.id ? 'Обновление данных' : typeLabels[currentType]['title'] }}</h1>
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
                                <label class="form-asterisk">Количество поступления</label>
                                <numeric-field type="number" class="form-control"
                                       :class="{'is-invalid': errors.quantity}"
                                       v-model.number="form.quantity" />

                                <div v-if="errors.quantity" class="error invalid-feedback">
                                    {{ errors.quantity }}
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
                                <span v-else>{{ nomenclatureOperation?.id ? 'Сохранить' : 'Добавить' }}</span>
                            </button>

                            <Link v-if="backRoute" :href="route(backRoute)" :class="{disabled: form.processing}"
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
import CustomSelect from "../../Shared/CustomSelect.vue";
import NumericField from "../../Shared/NumericField.vue";

export default {
    props: ['nomenclatures', 'nomenclatureOperation', 'currentType', 'backRoute', 'errors'],
    components: {NumericField, CustomSelect, Head, Link},
    data() {
        return {
            form: useForm({
                type: this.nomenclatureOperation?.type || this.currentType,
                nomenclature_id: this.nomenclatureOperation?.nomenclature_id,
                quantity: this.nomenclatureOperation?.quantity,
            }),
            typeLabels: {
                1: {
                    title: 'Списание',
                }
            }
        }
    },
    methods: {
        submit() {
            if (!this.nomenclatureOperation?.id) {
                this.form.post(route('nomenclature-operations.store'));
                return;
            }

            this.form.put(route('nomenclature-operations.update', this.nomenclatureOperation.id))
        }
    }
}
</script>
