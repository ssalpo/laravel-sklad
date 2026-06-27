<template>
    <Head>
        <title>Цены</title>
    </Head>

    <div class="content-header">
        <div class="container-fluid">
            <h5 class="m-0">Цены для {{ client.name }}</h5>
        </div>
    </div>

    <div class="content">
        <div class="container-fluid">
            <div class="card">
                <div class="card-body">
                    <form class="mb-4" @submit.prevent="submit">
                        <div class="row align-items-end">
                            <div class="col-12 col-md-6">
                                <div class="form-group mb-md-0">
                                    <label class="form-asterisk">Номенклатура</label>
                                    <select class="form-control"
                                            :class="{'is-invalid': errors.nomenclature_id}"
                                            v-model.number="form.nomenclature_id">
                                        <option :value="null">Выберите номенклатуру</option>
                                        <option :value="nomenclature.id"
                                                v-for="nomenclature in selectableNomenclatures">
                                            {{ nomenclature.name }}
                                        </option>
                                    </select>

                                    <div v-if="errors.nomenclature_id" class="error invalid-feedback">
                                        {{ errors.nomenclature_id }}
                                    </div>
                                </div>
                            </div>

                            <div class="col-12 col-md-3 mt-3 mt-md-0">
                                <div class="form-group mb-md-0">
                                    <label class="form-asterisk">Цена</label>
                                    <numeric-field
                                        :precision="2"
                                        class="form-control"
                                        :class="{'is-invalid': errors.price}"
                                        v-model="form.price"
                                    />

                                    <div v-if="errors.price" class="error invalid-feedback">
                                        {{ errors.price }}
                                    </div>
                                </div>
                            </div>

                            <div class="col-12 col-md-3 mt-3 mt-md-0 text-right">
                                <button type="submit" :disabled="form.processing || selectableNomenclatures.length === 0"
                                        class="btn btn-primary">
                                    <span v-if="form.processing">
                                        <i class="fas fa-spinner fa-spin"></i> Сохранение...
                                    </span>
                                    <span v-else>{{ editingClientPriceTemplate ? 'Сохранить' : 'Добавить' }}</span>
                                </button>

                                <button v-if="editingClientPriceTemplate" type="button" :disabled="form.processing"
                                        class="btn btn-default ml-2" @click="cancelEdit">
                                    Отменить
                                </button>
                            </div>
                        </div>
                    </form>

                    <div class="table-responsive">
                        <table class="table table-bordered text-nowrap">
                            <thead>
                            <tr>
                                <th style="width: 10px">#</th>
                                <th>ID номенклатуры</th>
                                <th>Номенклатура</th>
                                <th>Цена</th>
                                <th width="80"></th>
                            </tr>
                            </thead>
                            <tbody>
                            <tr v-for="(clientPriceTemplate, index) in clientPriceTemplates.data">
                                <td :data-id="clientPriceTemplate.id">
                                    {{ ((clientPriceTemplates.current_page - 1) * clientPriceTemplates.per_page) + index + 1 }}
                                </td>
                                <td>{{ clientPriceTemplate.nomenclature_id }}</td>
                                <td>{{ clientPriceTemplate.nomenclature }}</td>
                                <td>{{ numberFormat(clientPriceTemplate.price) }} сом.</td>
                                <td class="text-center">
                                    <button type="button" class="btn btn-sm btn-outline-primary mr-2"
                                            @click="edit(clientPriceTemplate)">
                                        <i class="fa fa-pencil-alt"></i>
                                    </button>

                                    <delete-btn
                                        :url="route('client-price-templates.destroy', {client: client.id, client_price_template: clientPriceTemplate.id})"
                                    />
                                </td>
                            </tr>
                            </tbody>
                        </table>
                    </div>
                </div>

                <div class="card-footer clearfix" v-if="clientPriceTemplates.links.length > 3">
                    <pagination :links="clientPriceTemplates.links"/>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
import {Head, useForm} from "@inertiajs/inertia-vue3";
import NumericField from "@/Shared/NumericField.vue";
import DeleteBtn from "@/Shared/DeleteBtn.vue";
import Pagination from "@/Shared/Pagination.vue";

export default {
    components: {Pagination, DeleteBtn, NumericField, Head},
    props: ['client', 'clientPriceTemplates', 'nomenclatures', 'usedNomenclatureIds', 'errors'],
    data() {
        return {
            editingClientPriceTemplate: null,
            form: useForm({
                nomenclature_id: null,
                price: null,
            }),
        }
    },
    computed: {
        selectableNomenclatures() {
            return this.nomenclatures.filter((nomenclature) => {
                return !this.usedNomenclatureIds.includes(nomenclature.id)
                    || nomenclature.id === this.editingClientPriceTemplate?.nomenclature_id;
            });
        }
    },
    methods: {
        submit() {
            if (this.editingClientPriceTemplate) {
                this.form.put(route('client-price-templates.update', {
                    client: this.client.id,
                    client_price_template: this.editingClientPriceTemplate.id
                }), {
                    preserveScroll: true,
                    onSuccess: () => this.cancelEdit(),
                });

                return;
            }

            this.form.post(route('client-price-templates.store', {client: this.client.id}), {
                preserveScroll: true,
                onSuccess: () => this.form.reset(),
            });
        },
        edit(clientPriceTemplate) {
            this.editingClientPriceTemplate = clientPriceTemplate;
            this.form.nomenclature_id = clientPriceTemplate.nomenclature_id;
            this.form.price = clientPriceTemplate.price;
        },
        cancelEdit() {
            this.editingClientPriceTemplate = null;
            this.form.reset();
            this.form.clearErrors();
        }
    }
}
</script>
