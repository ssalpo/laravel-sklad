<template>
    <div class="btn-group d-block custom-select-wrapper" :class="{'full-width': this.full}">
        <button :class="['btn dropdown-toggle custom-select-btn', btnClass]" type="button"
                data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
            {{ selected || placeholder || '&nbsp;' }}
        </button>
        <div class="dropdown-menu" aria-labelledby="dropdownMenuButton">
            <div class="custom-select-search-input" v-if="searchable">
                <input type="text" @keyup="onKeyUp" class="form-control form-control-sm"
                       placeholder="Найти">
            </div>

            <a class="dropdown-item"
               href="javascript:void(0)"
               :class="{disabled: disabledValues.includes(option[returnValueKey]), active: option[returnValueKey] === value}"
               @click.prevent="!disabledValues.includes(option[returnValueKey]) ? onChange(option): null"
               v-for="option in filteredOptions">{{ option[labelKey] }}</a>
        </div>
    </div>
</template>

<script>
import find from "lodash/find";
import get from "lodash/get";
import debounce from "lodash/debounce";

export default {
    props: {
        searchable: {
            type: Boolean,
            default: () => false
        },
        disabledValues: {
            type: Array,
            default: () => []
        },
        full: {
            type: Boolean
        },
        btnClass: {
            type: String,
            default: () => 'btn-default'
        },
        placeholder: {
            type: String,
            default: () => 'Выберите элемент'
        },
        options: {
            type: Array,
            default: () => []
        },
        value: {
            type: [String, Number],
            default: () => null
        },
        labelKey: {
            type: String,
            default: () => 'text'
        },
        returnValue: {
            type: String,
            default: () => 'string'
        },
        returnValueKey: {
            type: String,
            default: () => 'id'
        },
    },
    data: () => ({
        searchQuery: '',
        selected: null
    }),
    created() {
        this.setSelected(this.value);
    },
    computed: {
        filteredOptions() {
            if (!this.searchable) {
                return this.options;
            }

            const regex = new RegExp(this.searchQuery, 'ig');

            return this.searchQuery
                ? this.options.filter(e => regex.test(e[this.labelKey]))
                : this.options;
        }
    },
    methods: {
        onKeyUp: debounce(function (e) {
            this.searchQuery = e.target.value;
        }, 500),
        findLabel(v) {
            return get(find(this.options, [this.returnValueKey, v]), this.labelKey)
        },
        onChange(option) {
            this.selected = option[this.labelKey];
            this.searchQuery = '';

            this.$emit('update:modelValue', option[this.returnValueKey])
        },
        setSelected(v) {
            this.selected = this.findLabel(v)
        }
    },
    watch: {
        value(v) {
            this.setSelected(v)
        }
    }
}
</script>

<style scoped>
.custom-select-wrapper.full-width,
.custom-select-wrapper.full-width .custom-select-btn,
.custom-select-wrapper.full-width .dropdown-menu {
    width: 100%;
}

.custom-select-wrapper.is-invalid .custom-select-btn {
    border-color: #dc3545;
    /*padding-right: 2.25rem!important;*/
    /*background-image: url("data:image/svg+xml,%3csvg xmlns='http://www.w3.org/2000/svg' width='12' height='12' fill='none' stroke='%23dc3545' viewBox='0 0 12 12'%3e%3ccircle cx='6' cy='6' r='4.5'/%3e%3cpath stroke-linejoin='round' d='M5.8 3.6h.4L6 6.5z'/%3e%3ccircle cx='6' cy='8.2' r='.6' fill='%23dc3545' stroke='none'/%3e%3c/svg%3e");*/
    /*background-repeat: no-repeat;*/
    /*background-position: right calc(0.375em + 0.1875rem) center;*/
    /*background-size: calc(0.75em + 0.375rem) calc(0.75em + 0.375rem);*/
}

.custom-select-btn {
    display: flex;
    align-items: center;
    justify-content: space-between;
}

.dropdown-menu {
    overflow-y: scroll;
    max-height: 200px;
}

.custom-select-search-input {
    padding: 0 15px 10px;
}
</style>
