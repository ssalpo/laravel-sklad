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
                            <label class="form-asterisk">Категория</label>
                            <select class="form-control"
                                    :class="{'is-invalid': errors.category_id}"
                                    v-model.trim="form.category_id">
                                <option :value="category.id" v-for="category in categories">{{category.name}}</option>
                            </select>

                            <div v-if="errors.category_id" class="error invalid-feedback">
                                {{ errors.category_id }}
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="form-asterisk">Цена продажи</label>
                            <input type="text" class="form-control"
                                   :class="{'is-invalid': errors.price_for_sale}"
                                   v-model.trim="form.price_for_sale">

                            <div v-if="errors.price_for_sale" class="error invalid-feedback">
                                {{ errors.price_for_sale }}
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="form-asterisk">Тип валюты</label>
                            <select class="form-control"
                                    :class="{'is-invalid': errors.currency_type}"
                                    v-model.number="form.currency_type">
                                <option :value="index" v-for="(currencyType, index) in $page.props.shared.currencyTypeLabels">{{currencyType}}</option>
                            </select>

                            <div v-if="errors.currency_type" class="error invalid-feedback">
                                {{ errors.currency_type }}
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
                                    :class="{'is-invalid': errors.unit_id}"
                                    v-model.trim="form.unit_id">
                                <option :value="unit.id" v-for="unit in units">{{unit.name}}</option>
                            </select>

                            <div v-if="errors.unit_id" class="error invalid-feedback">
                                {{ errors.unit_id }}
                            </div>
                        </div>
                    </div>
                    <!-- /.card-body -->

                    <div class="card-footer">
                        <button type="submit" :disabled="form.processing" class="btn btn-primary">
                            <span v-if="form.processing">
                                <i class="fas fa-spinner fa-spin"></i> Сохранение...
                            </span>
                            <span v-else>{{ nomenclature?.id ? 'Сохранить' : 'Добавить' }}</span>
                        </button>

                        <Link :href="route('nomenclatures.index')" :class="{disabled: form.processing}" class="btn btn-default ml-2">Отменить</Link>
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
                price_for_sale: this.nomenclature?.price_for_sale,
                currency_type: this.nomenclature?.currency_type,
                type: this.nomenclature?.type,
                unit_id: this.nomenclature?.unit_id,
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
