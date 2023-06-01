<template>
    <div class="content-wrapper bg-white">
        <!-- <app-page-top-section :title="$t('leave_settings')"/> -->
        <!-- <app-tab
            :tabs="tabs"
            icon="settings"
        /> -->
        <!-- <div class="row">
            <div class="col-12">
                <div class="card-header bg-transparent p-primary d-flex justify-content-start align-items-center bg-gray-1 rounded-top-2">
                    <div class="bg-image mr-2">
                        <app-icon name="file-text" stroke="white" />
                    </div>
                    <h5 class="card-title text-capitalize mb-0">
                        {{ $t('leave_settings') }}
                    </h5>
                </div>
            </div>
            <div class="col-12">
                <div class="px-5 py-4 bg-sky-3 rounded-bottom-2">
                    <form ref="form" :data-url='this.selectedUrl ? this.selectedUrl : apiUrl.LEAVE_PERIODS'>
                        <div class="row align-items-center">
                            <div class="col-3">
                                <app-form-group :label="$t('Số đơn tối đa')" type="text" v-model="formData.start_date"
                                    placeholder="số đơn tối đa" :required="true"
                                    :error-message="$errorMessage(errors, 'start_date')" />
                            </div>
                            <div class="col-3">
                                <app-form-group :label="$t('Trên')" type="select" v-model="formData.type" :list="getLeaveTypes"
                                :placeholder="$placeholder('type', '')" :error-message="$errorMessage(errors, 'type')" />
                            </div>
                            <div class="col-2">
                                <app-form-group :label="$t('Cho phép gửi đơn sau')" type="text" v-model="formData.start_date"
                                    placeholder="Cho phép gửi đơn sau" :required="true"
                                    :error-message="$errorMessage(errors, 'start_date')" />
                            </div>
                            <div class="col-2">
                                <app-form-group :label="$t('Đơn vị')" type="select" v-model="formData.type" :list="getLeaveTypes"
                                :placeholder="$placeholder('type', '')" :error-message="$errorMessage(errors, 'type')" />
                            </div>
                            <div class="col-2">
                                <app-submit-button btn-class="d-inline-flex btn-block text-center btn-primary bg-green-1 border-color-green" iconButton="save" stroke="#FFF"
                                :label="$t('update')" :loading="loading" @click="submitData" />
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div> -->
    </div>
</template>

<script>

// export default {
//     name: "LeaveSettingLayout",
//     data() {
//         return {
//             tabs: [
//                 {
//                     headerButton: this.$can('create_leave_types') && {
//                         "label": this.$fieldTitle('add', 'leave_type', true),
//                     },
//                     name: this.$t('leave_type'),
//                     title: this.$t('leave_type'),
//                     component: "app-leave-types-setting",
//                     permission: this.$can('view_leave_settings')
//                 },
//                 {
//                     name: this.$t('allowance_policy'),
//                     title: this.$t('allowance_policy'),
//                     component: "app-leave-allowance-policy",
//                     permission: this.$can('manage_leave_allowance_policy')
//                 },
//                 {
//                     name: this.$t('approval'),
//                     title: this.$t('approval'),
//                     component: "app-leave-approval-setting",
//                     permission: this.$can('manage_leave_approval')
//                 },
//             ]
//         }
//     }
// }
</script>

<style scoped>

</style>