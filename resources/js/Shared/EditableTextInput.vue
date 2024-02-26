<template>
    <div v-if="form[fieldName] && !isEdit">
        {{ form[fieldName] }}

        <div>
            <small>
                <a href="" @click.prevent="isEdit = true">
                    редактировать
                </a>
            </small>
        </div>
    </div>
    <div v-else-if="isEdit">
        <div class="input-group input-group-sm">
            <input type="text"
                   v-bind="$attrs"
                   v-model="form[fieldName]"
                   @keydown.enter="submit"
                   :class="{'is-invalid': form.errors.has(fieldName)}"
                   class="form-control form-control-sm" />

            <div class="input-group-append">
                <button @click="submit" :disabled="form.busy || !form[fieldName]" class="btn btn-xs btn-success" type="button">
                    <i class="fa fa-check"></i>
                </button>

                <button @click="isEdit = false" :disabled="form.busy" class="btn btn-xs btn-danger" type="button">
                    <span class="fa fa-times"></span>
                </button>
            </div>
        </div>
    </div>
    <button v-else type="button" @click="isEdit = true" class="btn btn-sm btn-outline-danger">
        Добавить комментарий
    </button>
</template>

<script>
import Form from 'vform'
import NumericField from "./NumericField.vue";

export default {
    components: {NumericField},
    props: {
        submitUrl: {
            type: String,
            required: true
        },
        fieldName: {
            type: String,
            required: true
        },
        value: String
    },
    data() {
        return {
            isEdit: false,
            form: new Form({
                [this.fieldName]: this.value
            })
        }
    },
    methods: {
        submit () {
            this.form
                .put(this.submitUrl)
                .then(() => {
                    this.isEdit = false
                    this.$emit('success', this.form)
                })
        }
    }
}
</script>
