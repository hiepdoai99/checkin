<template>
    <div>
        <app-default-button
            btn-class="btn btn-success mr-2"
            :title="$t('settings')"
            v-if="$can('view_leave_settings')"
            :url="apiUrl.LEAVE_SETTINGS_FRONT_END"
        />

        <div class="btn-group dropdown">
            <app-default-button
                v-if="this.$can('create_order') || requestButton"
                :title="buttonTitle"
                @click="$emit('open-model')"
            />
        </div>
    </div>
</template>

<script>
export default {
    name: "LeaveTopButtons",
    props: {
        requestButton: {
            type: Boolean,
            default: false
        },
    },
    computed: {
        buttonTitle() {
            // return this.$can('create_leave') ? (this.$t('create_leave')) : this.$t('create_leave');
            return this.$t('create_order');
        }
    }
}
</script>
