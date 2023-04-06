<template>
    <select class="form-control"></select>
</template>

<script>

export default {
    props: {
        modelValue: {
            type: [Number, String],
            default: null
        },
        options: {
            type: Array,
            default: () => []
        },
        config: {
            type: Object,
            default: {}
        },
        clearable: {
            type: Boolean,
            default: false
        },
        searchable: {
            type: Boolean,
            default: true
        },
        prefetch: {
            type: Boolean,
            default: false
        },
        url: String,
        placeholder: String,
        textKey: {
            type: String,
            default: 'name'
        },
        idKey: {
            type: String,
            default: 'id'
        },
    },
    data() {
        return {
            isFetched: false,
            listOptions: this.options
        }
    },
    computed: {
        selectConfig() {
            if (this.clearable && !this.placeholder) {
                alert('Please enter "placeholder" property.');
            }

            return {
                selectOnClose: true,
                dropdownAutoWidth: true,
                minimumResultsForSearch: this.searchable ? 0 : -1,
                allowClear: this.clearable,
                placeholder: this.placeholder,
                ...this.config
            };
        }
    },
    mounted: function () {
        let vm = this;

        $(this.$el)
            .select2({...this.selectConfig, data: this.listOptions})
            .val(this.modelValue)
            .trigger("change")
            .on("change", function () {
                vm.$emit("update:modelValue", this.value)
            });

        this.prefetchFromServer()
    },
    watch: {
        modelValue: {
            deep: true,
            handler: function (v) {
                $(this.$el)
                    .val(v)
                    .trigger("change");
            }
        },
        options: {
            deep: true,
            handler: function (options) {
                this.listOptions = options
            }
        },
        listOptions: {
            deep: true,
            handler: function (options) {
                $(this.$el)
                    .empty()
                    .select2({...this.selectConfig, data: options});
            }
        }
    },
    methods: {
        convertToOptionData(data) {
            return {id: data[this.idKey], text: data[this.textKey]};
        },
        prefetchFromServer() {
            if (this.prefetch && !this.url) {
                alert('Please enter "url" property.');

                return;
            }

            if (this.prefetch && this.url) {
                axios.post(this.url)
                    .then((r) => {
                        this.listOptions = r.data.map(this.convertToOptionData)
                    })
            }
        }
    },
    destroyed: function () {
        $(this.$el)
            .off()
            .select2("destroy");
    }
}
</script>

<style scoped>

</style>
