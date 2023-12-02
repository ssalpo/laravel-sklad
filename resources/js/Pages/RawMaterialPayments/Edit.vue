<template>
    <Head>
        <title>{{rawMaterialPayment?.id ? 'Обновление данных' : 'Новое погашение'}}</title>
    </Head>

    <div class="content-header">
        <div class="container-fluid">
            <h1 class="m-0">{{ rawMaterialPayment?.id ? 'Обновление данных клиента' : 'Новый клиент' }}</h1>
        </div>
    </div>

    <div class="content">
        <div class="container-fluid">
            <div class="card card-primary">
                <!-- form start -->
                <form @submit.prevent="submit">
                    <div class="card-body">
                        <div class="form-group">
                            <label class="form-asterisk">Сумма погашения</label>
                            <numeric-field :precision="2" class="form-control"
                                           :class="{'is-invalid': errors.amount}"
                                           v-model="form.amount" />
                            <div v-if="errors.amount" class="error invalid-feedback">
                                {{ errors.amount }}
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
                    </div>
                    <!-- /.card-body -->

                    <div class="card-footer">
                        <button type="submit" :disabled="form.processing" class="btn btn-primary">
                            <span v-if="form.processing">
                                <i class="fas fa-spinner fa-spin"></i> Сохранение...
                            </span>
                            <span v-else>{{ rawMaterialPayment?.id ? 'Сохранить' : 'Добавить' }}</span>
                        </button>

                        <Link :href="route('raw-materials.raw-material-payments.index', this.rawMaterialId)" :class="{disabled: form.processing}" class="btn btn-default ml-2">Отменить</Link>
                    </div>
                </form>
            </div>
        </div>
    </div>
</template>
<script>
import {Head, Link, useForm} from "@inertiajs/inertia-vue3";
import { vMaska } from "maska";
import NumericField from "../../Shared/NumericField.vue";

export default {
    props: ['rawMaterialId', 'rawMaterialPayment', 'rawMaterials', 'errors'],
    components: {NumericField, Head, Link},
    directives: { maska: vMaska },
    data() {
        return {
            form: useForm({
                raw_material_id: this.rawMaterialId,
                amount: this.rawMaterialPayment?.amount,
                comment: this.rawMaterialPayment?.comment,
            }),
        }
    },
    methods: {
        submit() {
            if (!this.rawMaterialPayment?.id) {
                this.form.post(route('raw-materials.raw-material-payments.store', this.rawMaterialId));
                return;
            }

            this.form.put(route('raw-materials.raw-material-payments.update', {
                'raw_material': this.rawMaterialId,
                'raw_material_payment': this.rawMaterialPayment.id
            }))
        }
    }
}
</script>
