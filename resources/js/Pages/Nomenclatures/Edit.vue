<template>
    <Head>
        <title>{{nomenclature?.id ? 'Обновление данных номенклатуры' : 'Новая номенклатура'}}</title>
    </Head>

    <div class="content-header">
        <div class="container">
            <h1 class="m-0">{{ nomenclature?.id ? 'Обновление данных номенклатуры' : 'Новая номенклатура' }}</h1>
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
                                <label class="form-asterisk">Наименование</label>
                                <input type="text" class="form-control"
                                       :class="{'is-invalid': errors.name}"
                                       v-model.trim="form.name">

                                <div v-if="errors.name" class="error invalid-feedback">
                                    {{ errors.name }}
                                </div>
                            </div>

                            <div class="form-group">
                                <label class="form-asterisk">Цена покупки</label>
                                <input type="text" class="form-control"
                                       v-money="{}"
                                       :class="{'is-invalid': errors.price}"
                                       v-model.trim="form.price">

                                <div v-if="errors.price" class="error invalid-feedback">
                                    {{ errors.price }}
                                </div>
                            </div>

                            <div class="form-group">
                                <label class="form-asterisk">Цена продажи</label>
                                <input type="text" class="form-control"
                                       v-money="{}"
                                       :class="{'is-invalid': errors.price_for_sale}"
                                       v-model.trim="form.price_for_sale">

                                <div v-if="errors.price_for_sale" class="error invalid-feedback">
                                    {{ errors.price_for_sale }}
                                </div>
                            </div>

                            <div class="form-group">
                                <label class="form-asterisk">Тип номенклатуры</label>
                                <select class="form-control"
                                        :class="{'is-invalid': errors.type}"
                                        v-model.number="form.type">
                                    <option :value="index" v-for="(nomenclatureType, index) in $page.props.shared.nomenclatureTypes">{{nomenclatureType}}</option>
                                </select>

                                <div v-if="errors.type" class="error invalid-feedback">
                                    {{ errors.type }}
                                </div>
                            </div>

                            <div class="form-group">
                            <label class="form-asterisk">Единица измерения</label>
                            <select class="form-control"
                                    :class="{'is-invalid': errors.unit}"
                                    v-model.trim="form.unit">
                                <option :value="index" v-for="(label, index) in $page.props.shared.unitLabels">{{ label }}</option>
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

                            <Link :href="route('nomenclatures.index')" :class="{disabled: form.processing}" class="btn btn-default ml-2">Отменить</Link>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</template>
<script>
import {Head, Link, useForm} from "@inertiajs/inertia-vue3";

export default {
    props: ['nomenclature', 'units', 'categories', 'errors'],
    components: {Head, Link},
    data() {
        return {
            form: useForm({
                name: this.nomenclature?.name,
                category_id: this.nomenclature?.category_id,
                price: this.nomenclature?.price,
                price_for_sale: this.nomenclature?.price_for_sale,
                type: this.nomenclature?.type,
                unit: this.nomenclature?.unit,
            }),
        }
    },
    methods: {
        submit() {
            if (!this.nomenclature?.id) {
                this.form.post(route('nomenclatures.store'));
                return;
            }

            this.form.put(route('nomenclatures.update', this.nomenclature.id))
        }
    }
}
</script>
