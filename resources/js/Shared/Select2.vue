<template>
    <select class="form-control"></select>
</template>

<script>
import isObject from "lodash/isObject"

export default {
    name: 'select2',
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
        isInvalid: {
            type: Boolean,
            default: false
        },
        ajax: {
            type: Boolean,
            default: false
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
        disabledValues: {
            type: Array,
            default: () => []
        },
        selected: {
            type: Object,
            default: () => {
            }
        }
    },
    data() {
        return {
            isFetched: false,
            listOptions: []
        }
    },
    created() {
        if (this.searchable && !this.ajax && !this.prefetch) {
            this.listOptions = this.options.map(this.convertToOptionData);
        }
    },
    computed: {
        selectConfig() {
            if (this.clearable && !this.placeholder) {
                alert('Please enter "placeholder" property.');
            }

            let config = {
                // selectOnClose: true,
                language: 'ru',
                dropdownAutoWidth: true,
                minimumResultsForSearch: this.searchable ? 0 : -1,
                allowClear: this.clearable,
                placeholder: this.placeholder,
                ...this.config
            };

            if (this.ajax) {
                config['minimumInputLength'] = 1;

                config['ajax'] = {
                    delay: 500,
                    url: this.url,
                    processResults: this.processAjaxResult
                };
            }

            return config;
        }
    },
    mounted: function () {
        let vm = this;

        this.setSelected(this.selected);

        $(this.$el)
            .select2({...this.selectConfig, data: this.listOptions})
            .val(this.modelValue)
            .trigger("change")
            .on("change", function () {
                vm.$emit("update:modelValue", this.value)
            })
            .on('select2:open', this.onOpen);

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
            immediate: true,
            deep: true,
            handler: function (options) {
                if (!this.prefetch) {
                    this.listOptions = options.map(this.convertToOptionData)
                }
            }
        },
        listOptions: {
            deep: true,
            handler: function (options) {
                $(this.$el)
                    .select2({...this.selectConfig, data: options})
                    .val(this.modelValue)
                    .trigger("change");
            }
        },
        selected: {
            deep: true,
            handler: function (v) {
                this.setSelected(v);
            }
        },
        isInvalid(status) {
            if(status) {
                $(this.$el).addClass('select2-invalid')
            } else {
                $(this.$el).removeClass('select2-invalid')
            }
        }
    },
    methods: {
        convertToOptionData(data) {
            let item = {id: data[this.idKey], text: data[this.textKey]};

            if (this.disabledValues.includes(item.id)) {
                item['disabled'] = true;
            }

            return item;
        },
        prefetchFromServer() {
            if (this.prefetch && !this.url) {
                alert('Please enter "url" property.');

                return;
            }

            if (this.prefetch && this.url) {
                axios.get(this.url)
                    .then((r) => {
                        this.listOptions = r.data.map(this.convertToOptionData)
                    })
            }
        },
        processAjaxResult(response) {
            return {
                results: response.map(this.convertToOptionData)
            }
        },

        setSelected(data) {
            if (!this.listOptions.length && isObject(data)) {
                this.listOptions = [
                    {
                        ...this.convertToOptionData(data),
                        selected: true
                    }
                ]
            }
        },
        onOpen(event) {
            $(document).find('.select2-search__field').addClass('form-control form-control-sm')
        }
    },
    destroyed: function () {
        $(this.$el)
            .off()
            .select2("destroy");
    }
}
</script>
