<template>
    <button type="button" class="btn" :class="[btnSize, btnStyle]" :disabled="loading" @click="submit">
        <span class="fa fa-trash"></span>
    </button>
</template>

<script>
export default {
    name: "delete-btn",
    props: {
        url: String,
        btnSize: {
            type: String,
            default: 'btn-sm'
        },
        btnStyle: {
            type: String,
            default: 'btn-outline-danger'
        },
        data: {
            type: Object,
            default: {}
        },
        showConfirm: {
            type: Boolean,
            default: true
        },
        confirmText: {
            type: String,
            default: 'Вы уверены что хотите удалить?'
        }
    },
    data: () => ({
        loading: false
    }),
    methods: {
        submit() {
            if (this.showConfirm && !confirm(this.confirmText)) {
                return;
            }

            this.loading = true;

            this.$inertia.delete(this.url, {
                data: this.data,
                preserveScroll: true,
                preserveState: true,
                onFinish: () => this.loading = false
            })
        }
    }
}
</script>
