<template>
    <Head>
        <title>{{employeeSalary?.id ? 'Обновление данных' : 'Выдать зарплату'}}</title>
    </Head>

    <div class="content-header">
        <div class="container-fluid">
            <h1 class="m-0">{{ employeeSalary?.id ? 'Обновление данных' : 'Выдать зарплату' }}</h1>
        </div>
    </div>

    <div class="content">
        <div class="container-fluid">
            <div class="card card-primary">
                <!-- form start -->
                <form @submit.prevent="submit">
                    <div class="card-body">
                        <div class="form-group">
                            <label class="form-asterisk">Сумма зарплаты</label>
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
                            <span v-else>{{ employeeSalary?.id ? 'Сохранить' : 'Добавить' }}</span>
                        </button>

                        <Link :href="route('employees.employee-salaries.index', this.employeeId)" :class="{disabled: form.processing}" class="btn btn-default ml-2">Отменить</Link>
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
    props: ['employeeId', 'employeeSalary', 'errors'],
    components: {NumericField, Head, Link},
    directives: { maska: vMaska },
    data() {
        return {
            form: useForm({
                employee_id: this.employeeId,
                amount: this.employeeSalary?.amount,
                comment: this.employeeSalary?.comment,
            }),
        }
    },
    methods: {
        submit() {
            if (!this.employeeSalary?.id) {
                this.form.post(route('employees.employee-salaries.store', {employee: this.employeeId}));
                return;
            }

            this.form.put(route('employees.employee-salaries.update', {
                employee: this.employeeId,
                employee_salary: this.employeeSalary.id
            }))
        }
    }
}
</script>
