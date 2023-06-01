<template>
    <button :class="'btn text-center '+btnClass"
            :type="type"
            @click.prevent="$emit('click')"
            :disabled="loading">
            <span class="w-100">
                <loader v-if="loading" :loader-color="'white-color'"/>
                <app-icon v-if="showIcon" :name="iconButton" :stroke="stroke" />
                <template v-if="!loading">
                {{ label }}
            </template>
        </span>
    </button>
</template>

<script>
import Loader from "../../../../core/components/preloaders/Loader";

export default {
    name: "SubmitButton",
    components: {
        'loader': Loader
    },
    props: {
        btnClass: {
            required: false,
            default: 'btn-primary'
        },
        label: {
            required: false,
            default: function () {
                return this.$t('save');
            }
        },
        loading: {
            required: false,
            default: function () {
                return false;
            }
        },
        type: {
            required: false,
            default: function () {
                return 'submit'
            }
        },
        icon: {
            required: false,
            default: function () {
                return 'fa fa-spin fa-spinner';
            }
        },
        showIcon: {
            type: Boolean,
            default: false,
        },
        iconButton: {
            type: String,
            default:'save',
        },
        stroke: {
            type: String,
            default:"#FFF",
        },
    },

}
</script>

<style scoped>

</style>
