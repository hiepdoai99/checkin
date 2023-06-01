<template>
    <div class="content-wrapper bg-white">
        <app-page-top-section :title="$t('leave_settings')"/>

        <!-- <app-tab
            :tabs="tabs"
            icon="settings"
        /> -->
        <div class="row">
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
        </div>
        <div class="row mt-3">
            <div class="col-12">
                <div class="card-header bg-transparent p-primary d-flex justify-content-start align-items-center bg-gray-1 rounded-top-2">
                    <div class="bg-image mr-2">
                        <app-icon name="file-text" stroke="white" />
                    </div>
                    <h5 class="card-title text-capitalize mb-0">
                        {{ $t('install_go_late_and_leave_early') }}
                    </h5>
                </div>
            </div>
            <div class="col-12">
                <div class="px-5 py-4 bg-sky-3">
                    <form ref="form" :data-url='this.selectedUrl ? this.selectedUrl : apiUrl.LEAVE_PERIODS'>
                        <div class="row align-items-center">
                            <div class="col-2">
                                <app-form-group :label="$t('Số đơn tối đa')" type="text" v-model="formData.start_date"
                                    placeholder="số đơn tối đa" :required="true"
                                    :error-message="$errorMessage(errors, 'start_date')" />
                            </div>
                            <div class="col-2">
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
                                <app-form-group :label="$t('Cho phép xin đơn sau')" type="text" v-model="formData.start_date"
                                    placeholder="Cho phép gửi đơn sau" :required="true"
                                    :error-message="$errorMessage(errors, 'start_date')" />
                            </div>
                            <div class="col-2">
                                <app-submit-button btn-class="d-inline-flex btn-block text-center btn-primary bg-green-1 border-color-green" iconButton="save" stroke="#FFF"
                                :label="$t('update')" :loading="loading" @click="submitData" />
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
        <div class="row mt-3">
            <div class="col-12">
                <div class="card-header bg-transparent p-primary d-flex justify-content-start align-items-center bg-gray-1 rounded-top-2">
                    <div class="bg-image mr-2">
                        <app-icon name="file-text" stroke="white" />
                    </div>
                    <h5 class="card-title text-capitalize mb-0">
                        {{ $t('meet_custom') }}
                    </h5>
                </div>
            </div>
            <div class="col-12">
                <div class="px-5 py-4 bg-sky-3">
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
                            <div class="col-3">
                                <app-form-group :label="$t('Cho phép gửi đơn sau')" type="text" v-model="formData.start_date"
                                    placeholder="Cho phép gửi đơn sau" :required="true"
                                    :error-message="$errorMessage(errors, 'start_date')" />
                            </div>
                            <div class="col-3">
                                <app-form-group :label="$t('Đơn vị')" type="select" v-model="formData.type" :list="getLeaveTypes"
                                :placeholder="$placeholder('type', '')" :error-message="$errorMessage(errors, 'type')" />
                            </div>
                            <div class="col-5">
                                <app-form-group :label="$t('Cho phép checkin bên ngoài')" type="switch" v-model="formData.type" :list="getLeaveTypes"
                                :error-message="$errorMessage(errors, 'type')" />
                            </div>
                            <div class="col-5">
                                <app-form-group :label="$t('Cho phép viết đơn nhiều ngày')" type="switch" v-model="formData.type" :list="getLeaveTypes"
                                 :error-message="$errorMessage(errors, 'type')" />
                            </div>
                            <div class="col-2">
                                <div class="d-flex justify-content-center" >
                                    <!-- <app-icon name='save' stroke="#FFF" /> -->
                                    <app-submit-button btn-class="d-inline-flex btn-block text-center btn-primary bg-green-1 border-color-green" iconButton="save" stroke="#FFF"
                                        :label=" $t('update')" :loading="loading" @click="submitData" />

                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
        <div class="row mt-3">
            <div class="col-12">
                <div class="card-header bg-transparent p-primary d-flex justify-content-start align-items-center bg-gray-1 rounded-top-2">
                    <div class="bg-image mr-2">
                        <app-icon name="clipboard" stroke="white" />
                    </div>
                    <h5 class="card-title text-capitalize mb-0">
                        {{ $t('over_time_setting') }}
                    </h5>
                </div>
            </div>
            <div class="col-12">
                <div class="px-5 py-4 bg-sky-3">
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
        </div>
        <div class="row mt-3">
            <div class="col-12">
                <div class="card-header bg-transparent p-primary d-flex justify-content-start align-items-center bg-gray-1 rounded-top-2">
                    <div class="bg-image mr-2">
                        <app-icon name="clock" stroke="white" />
                    </div>
                    <h5 class="card-title text-capitalize mb-0">
                        {{ $t('forget_check_in') }}
                    </h5>
                </div>
            </div>
            <div class="col-12">
                <div class="px-5 py-4 bg-sky-3">
                    <form ref="form" :data-url='this.selectedUrl ? this.selectedUrl : apiUrl.LEAVE_PERIODS'>
                        <div class="row align-items-center">
                            <div class="col-4">
                                <app-form-group :label="$t('Số đơn tối đa')" type="text" v-model="formData.start_date"
                                    placeholder="số đơn tối đa" :required="true"
                                    :error-message="$errorMessage(errors, 'start_date')" />
                            </div>
                            <div class="col-4">
                                <app-form-group :label="$t('Trên')" type="select" v-model="formData.type" :list="getLeaveTypes"
                                :placeholder="$placeholder('type', '')" :error-message="$errorMessage(errors, 'type')" />
                            </div>
                            <div class="col-4">
                                <app-form-group :label="$t('Cho phép gửi đơn sau')" type="text" v-model="formData.start_date"
                                    placeholder="Cho phép gửi đơn sau" :required="true"
                                    :error-message="$errorMessage(errors, 'start_date')" />
                            </div>
                            <div class="col-4">
                                <app-form-group :label="$t('Đơn vị')" type="select" v-model="formData.type" :list="getLeaveTypes"
                                :placeholder="$placeholder('type', '')" :error-message="$errorMessage(errors, 'type')" />
                            </div>
                            <div class="col-4">
                                <app-form-group :label="$t('Mức công nhận')" type="text" v-model="formData.start_date"
                                    placeholder="VD: 50%" :required="true" :error-message="$errorMessage(errors, 'start_date')" />
                            </div>
                            <div class="col-2">
                                <app-form-group :label="$t('Lấy công trong ngày')" type="text" v-model="formData.start_date"
                                    placeholder="VD: > 0.5" :required="true" :error-message="$errorMessage(errors, 'start_date')" />
                            </div>
                            <div class="col-2">
                                <app-submit-button btn-class="d-inline-flex btn-block text-center btn-primary bg-green-1 border-color-green" iconButton="save" stroke="#FFF"
                                :label="$t('update')" :loading="loading" @click="submitData" />  
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
        <div class="row mt-3">
            <div class="col-12">
                <div class="card-header bg-transparent p-primary d-flex justify-content-start align-items-center bg-gray-1 rounded-top-2">
                    <div class="bg-image mr-2">
                        <app-icon name="folder" stroke="white" />
                    </div>
                    <h5 class="card-title text-capitalize mb-0">
                        {{ $t('setting_account') }}
                    </h5>
                </div>
            </div>
            <div class="col-12">
                <div class="px-5 py-4 bg-sky-3">
                    <form ref="form" :data-url='this.selectedUrl ? this.selectedUrl : apiUrl.LEAVE_PERIODS'>
                        <div class="row align-items-center">
                            <div class="col-5">
                                <app-form-group :label="$t('Cài đặt tài khoản đủ công')" type="multi-select" v-model="formData.type" :list="getAccount"
                                :placeholder="$placeholder('account', '')" :error-message="$errorMessage(errors, 'account')" />
                            </div>
                            <div class="col-5">
                                <app-form-group :label="$t('Cài đặt tài khoản không nằm trong BXH')" type="multi-select" v-model="formData.type" :list="getAccount"
                                :placeholder="$placeholder('account', '')" :error-message="$errorMessage(errors, 'account')" />
                            </div>
                            <div class="col-2">
                                <app-submit-button btn-class="d-inline-flex btn-block text-center btn-primary bg-green-1 border-color-green" iconButton="save" stroke="#FFF"
                                :label="$t('update')" :loading="loading" @click="submitData" />  
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
        <div class="row mt-3">
            <div class="col-12">
                <div class="card-header bg-transparent p-primary d-flex justify-content-start align-items-center bg-gray-1 rounded-top-2">
                    <div class="bg-image mr-2">
                        <app-icon name="settings" stroke="white" />
                    </div>
                    <h5 class="card-title text-capitalize mb-0">
                        {{ $t('Cài đặt lý do nghỉ phép') }}
                    </h5>
                </div>
            </div>
            <div class="col-12">
                <div class="px-5 py-4 bg-sky-3">
                    <app-table :id="'test-table-core'" 
                        :options="options" 
                        @action="getAction" 
                        @getRows="getSelectedRows"
                        @getFilteredValues="filteredValues"
                    />
                    <!-- <modal v-if="isShowModal" @close-modal="closeModal" /> -->
                    <app-confirmation-modal v-if="isShowDeleteModal" icon="check-circle" title="Hand's Up"
                        sub-title="This content will be deleted permanently" message="Are you sure?" modal-class="success"
                        modal-id="delete-modal" @confirmed="confirmed" @cancelled="cancelled" />
                </div>
            </div>
        </div>
    </div>
</template>

<script>
import FormHelperMixins from "../../../../common/Mixin/Global/FormHelperMixins";
import ModalMixin from "../../../../common/Mixin/Global/ModalMixin";
import { addSelectInSelectArray } from "../../../../common/Helper/Support/FormHelper";
import Modal from "../../../../common/Components/Helper/Modal.vue";

export default {
    name: "LeavePeriodCreateEditModal",
    mixins: [FormHelperMixins, ModalMixin],
    // methods: {

    // },
    computed: {
        getLeaveTypes() {
            return addSelectInSelectArray([
                { id: 'paid', value: this.$t('10') },
                { id: 'unpaid', value: this.$t('20') },
            ], 'value', 'type');
        },
        getAccount() {
            return addSelectInSelectArray([
                { id: '1', value: 'LVH' },
                { id: '2', value: 'ĐTĐ' },
            ], 'name', 'account');
        }
    },
    components: { Modal },
    data() {
        return {
            defaultColumns: [
                // {},
                {
                    title: 'ID',
                    type: 'number',
                    key: 'id',
                    isVisible: true,
                    modifier: (value, row) => {
                        return `1`;
                    }
                },
                {
                    title: 'Name',
                    type: 'string',
                    key: 'name',
                    isVisible: true,
                    modifier: (value) => {
                        return `<p class="text-right m-0">${value}</p>`
                    }
                },
                {
                    title: 'Tên lý do',
                    type: 'string',
                    key: 'nameDesc',
                    className: 'success', 
                },
                {
                    title: 'Hình thức',
                    type: 'text',
                    key: 'age',
                    // className: 'btn btn-success',
                    // icon: 'check',
                    // label: 'click me',
                },
                {
                    title: 'Số đơn',
                    type: 'dynamic-content',
                    key: 'name',
                    // label: 'click me',
                    // icon: 'check',
                    // className: 'btn btn-success',
                },
                {
                    title: 'Số ngày',
                    type: 'number',
                    key: 'number',
                    isVisible: true,
                    // componentName: 'test-avatar-group',
                },
                {
                    title: 'Hành động',
                    type: 'action',
                    isVisible: true,
                    key: 'action'
                },
            ],
            options: {
                name: 'languages',
                url: 'notifications',
                actions:
                    [
                        {},
                        {
                            title: 'Edit',
                            icon: 'edit',
                            type: 'none',
                            component: 'test-modal',
                            url: 'edit-test',
                            uniqueKey: 'invoice',
                        },
                        {
                            title: 'Delete',
                            icon: 'trash',
                            component: 'test-modal',
                            type: 'none',
                            url: '',
                        },
                    ],
            },
            actions: [
                {
                    "title": "Edit",
                },
                {
                    "title": "Delete",
                }
            ]
        }
    },
    created() {
        this.options.columns = this.defaultColumns;
    },
    methods: {
        afterSuccess({ data }) {
            this.formData = {};
            $('#leave-period-modal').modal('hide');
            this.$emit('input', false);
            this.toastAndReload(data.message, 'leave-period-table')
        },
        afterSuccessFromGetEditData({ data }) {
            this.formData.start_date = new Date(data.start_date);
            this.formData.end_date = new Date(data.end_date);
            this.preloader = false;
        },
        // 
        getSelectedRows(value) {
            // console.log(value);
        },
        filteredValues(value) {
            // console.log(value);
        },
        getAction(row, actionObj, active) {
            // console.log(actionObj.title);
            if (actionObj.title == 'Edit') {
                this.openModal();
            } else if (actionObj.title == 'Delete') {
                this.openDeleteModal();
            }
        },
        openModal() {
            this.isShowModal = true;
    
            setTimeout(function () {
                $("#exampleModal").modal('show');
            });
        },
        openDeleteModal() {
            this.isShowDeleteModal = true;
    
            setTimeout(function () {
                $("#delete-modal").modal('show');
            });
        },
        confirmed() {
            this.isShowDeleteModal = false;
            console.log('Clicked Confirmed');
        },
        cancelled() {
            this.isShowDeleteModal = false;
            console.log('Clicked Canceled');
        },
        closeModal() {
            this.isShowModal = false;
        },
        testCol() {
            this.options.columns = [...this.defaultColumns, {
                'title': 'Test',
                'type': 'text',
                'key': 'invoice',
                'isVisible': true
            }]
            this.options.filters.find(item => item.type === 'radio').initValue = 1
            this.options.filters.find(item => item.type === 'avatar-filter').active = 3
            this.options.filters.find(item => item.type === 'dropdown-menu-filter').initValue = 1
        },
        testColRev() {
            this.options.columns = [...this.defaultColumns]
        }
    }
}

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