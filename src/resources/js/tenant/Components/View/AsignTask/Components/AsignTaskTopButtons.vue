<template>
    <div>
        <!-- <app-default-button
            btn-class="btn btn-success mr-2"
            :title="$t('settings')"
            v-if="$can('view_leave_settings')"
            :url="apiUrl.LEAVE_SETTINGS_FRONT_END"
        /> -->

        <div class="btn-group dropdown">
            <app-default-button
                v-if="this.$can('create_order') || requestButton"
                :title="buttonTitle"
                @click="$emit('open-model')"
            />
            <!-- <button
                v-if="requestButton && this.$can('assigned_task')"
                class="btn btn-primary rounded-right px-3"
                data-toggle="dropdown"
                aria-haspopup="true"
                aria-expanded="false">
                <i class="fas fa-chevron-down fa-sm"></i>
            </button> -->
            <!-- <div v-if="requestButton && this.$can('assigned_task')" class="dropdown-menu dropdown-menu-right">
                <a class="dropdown-item" href="#" @click="$emit('open-model', true)">
                    {{ $t('request_leave') }}
                </a>
            </div> -->
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
            return this.$t('add_assign_task');
            // return this.$can('assigned_task') ? this.$t('add_assign_task') : this.$t('assigned_task');
        }
    }
}
</script>
