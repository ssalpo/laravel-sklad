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
                            <label class="form-asterisk">Количество</label>
                            <input type="text" class="form-control"
                                   :class="{'is-invalid': errors.quantity}"
                                   v-model.number="form.quantity">
                            <div v-if="errors.quantity" class="error invalid-feedback">
                                {{ errors.quantity }}
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="form-asterisk">Единица измерения количества</label>

                            <select class="form-control"
                                    :class="{'is-invalid': errors.unit}"
                                    v-model.trim="form.unit">
                                <option :value="index" v-for="(label, index) in $page.props.shared.unitLabels">{{ label }}</option>
                            </select>

                            <div v-if="errors.unit" class="error invalid-feedback">
                                {{ errors.unit }}
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="form-asterisk">Сумма</label>
                            <input type="text" class="form-control"
                                   :class="{'is-invalid': errors.price}"
                                   v-model.number="form.price">
                            <div v-if="errors.price" class="error invalid-feedback">
                                {{ errors.price }}
                            </div>
                        </div>
                    </div>
                    <!-- /.card-body -->

                    <div class="card-footer">
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
                </form>
            </div>
        </div>
    </div>
</template>
<script>
import {Head, Link, useForm} from "@inertiajs/inertia-vue3";

export default {
    props: ['mixtureCompositionId', 'mixtureCompositionItem', 'units', 'nomenclatures', 'errors'],
    components: {Head, Link},
    data() {
        return {
            form: useForm({
                nomenclature_id: this.mixtureCompositionItem?.nomenclature_id,
                price: this.mixtureCompositionItem?.price,
                quantity: this.mixtureCompositionItem?.quantity,
                unit: this.mixtureCompositionItem?.unit,
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
    }
}
</script>
