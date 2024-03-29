<template>
    <Head>
        <title>{{nomenclatureArrival?.id ? 'Обновление данных' : 'Новый приход'}}</title>
    </Head>

    <div class="content-header">
        <div class="container-fluid">
            <h1 class="m-0">{{ nomenclatureArrival?.id ? 'Обновление данных' : 'Новый приход' }}</h1>
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
                                <label class="form-asterisk">Расчет по количеству коробок</label>
                                <div class="row">
                                    <div class="col">
                                        <numeric-field class="form-control"
                                               placeholder="К-во коробок"
                                               v-model="box.quantity" />
                                    </div>
                                    <div class="col">
                                        <numeric-field class="form-control"
                                               placeholder="К-во в одной коробке"
                                               v-model="box.quantityInSingleBox" />
                                    </div>
                                </div>
                            </div>

                            <div class="form-group">
                                <label class="form-asterisk">Количество поступления</label>
                                <numeric-field  type="number"
                                       class="form-control"
                                       :class="{'is-invalid': errors.quantity}"
                                       v-model="form.quantity" />

                                <div v-if="errors.quantity" class="error invalid-feedback">
                                    {{ errors.quantity }}
                                </div>
                            </div>

                            <div class="form-group">
                                <label class="form-asterisk">Единица измерения</label>
                                <select disabled class="form-control"
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
                                <label>Комментарий</label>
                                <input type="text" class="form-control"
                                       :class="{'is-invalid': errors.comment}"
                                       v-model.trim="form.comment">

                                <div v-if="errors.comment" class="error invalid-feedback">
                                    {{ errors.comment }}
                                </div>
                            </div>

                            <div class="form-group">
                                <label>Дата прихода</label>
                                <input type="text" class="form-control"
                                       v-maska data-maska="##.##.#### ##:##"
                                       placeholder="ДД.ММ.ГГГГ ЧЧ:ММ"
                                       :class="{'is-invalid': errors.arrival_at}"
                                       v-model="form.arrival_at"/>

                                <div v-if="errors.arrival_at" class="error invalid-feedback">
                                    {{ errors.arrival_at }}
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
                                <span v-else>{{ nomenclatureArrival?.id ? 'Сохранить' : 'Добавить' }}</span>
                            </button>

                            <Link :href="route('nomenclature-arrivals.index')" :class="{disabled: form.processing}"
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
import find from "lodash/find";
import get from "lodash/get";
import {vMaska} from "maska";
import {Head, Link, useForm} from "@inertiajs/inertia-vue3";
import CustomSelect from "../../Shared/CustomSelect.vue";
import NumericField from "../../Shared/NumericField.vue";

export default {
    props: ['nomenclatureArrival', 'nomenclatures', 'currentDate', 'errors'],
    components: {NumericField, CustomSelect, Head, Link},
    directives: {maska: vMaska},
    data() {
        return {
            box: {
                quantity: null,
                quantityInSingleBox: null
            },
            form: useForm({
                nomenclature_id: this.nomenclatureArrival?.nomenclature_id,
                quantity: this.nomenclatureArrival?.quantity,
                unit: this.nomenclatureArrival?.unit,
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
            this.form.unit = get(find(this.nomenclatures, {'id': value}), 'unit');
        },
        box: {
            deep: true,
            handler() {
                this.form.quantity = this.box.quantity * this.box.quantityInSingleBox
            }
        }
    }
}
</script>
