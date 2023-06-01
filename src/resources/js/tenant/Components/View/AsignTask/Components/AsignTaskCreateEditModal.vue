<!-- <template>
    <modal id="assign-task-create-edit-modal" v-model="showModal" size="large" :title="modalTitle" @submit="submit"
        :loading="loading" :preloader="preloader">
        <form ref="form" enctype="multipart/form-data" @submit.prevent="submitData">
            <app-form-group type="text" :text-area-rows="4" :required="true" v-model="formData.name"
                :label="$t('title')" :error-message="$errorMessage(errors, 'note')"
                :placeholder="$t('title')" />
            <app-form-group-selectable v-if="assignPermissions" type="search-select" v-model="formData.user_id"
                :label="$t('employee')" list-value-field="full_name" :required="true"
                :error-message="$errorMessage(errors, 'employee_id')" :placeholder="$t('search_and_select_an_employee')"
                :fetch-url="`${apiUrl.TENANT_SELECTABLE_USER}?without=admin&employee=only&with_auth=yes`" />
            <app-overlay-loader v-if="modalPreloader" />
            <div :class="{ 'loading-opacity': modalPreloader }">
                <p v-if="formData.employee_id">
                    {{ $t('leave_availability') }}
                    <a class="ml-2" data-toggle="collapse" href="#leaveAvailability" aria-expanded="false"
                        @click="ariaExpanded = !ariaExpanded" aria-controls="leaveAvailability">
                        {{ ariaExpanded ? $t('hide') : $t('show') }}
                    </a>
                </p>
                <div class="row">
                    <div class="col-md-6">
                        <app-form-group type="date" v-model="formData.start_date" :label="$t('start_date')"
                            :required="true" :error-message="$errorMessage(errors, 'start_date')"
                            :placeholder="$t('enter_start_date')" />
                    </div>
                    <div class="col-md-6">
                        <app-form-group type="date" v-model="formData.end_date" :label="$t('end_date')" :required="true"
                            :error-message="$errorMessage(errors, 'end_date')" :placeholder="$t('enter_end_date')" />
                    </div>
                </div>
                <app-form-group type="textarea" :text-area-rows="4" v-model="formData.description"
                    :label="$t('desc_work')" :error-message="$errorMessage(errors, 'note')"
                    :placeholder="$t('add_reason_note_here')" />
            </div>
        </form>
    </modal>
</template>

<script>
import ModalMixin from "../../../../../common/Mixin/Global/ModalMixin";
import FormHelperMixins from "../../../../../common/Mixin/Global/FormHelperMixins";
import { axiosGet, axiosPost } from "../../../../../common/Helper/AxiosHelper";
import { addChooseInSelectArray, formDataAssigner } from "../../../../../common/Helper/Support/FormHelper";
import { formatDateForServer } from "../../../../../common/Helper/Support/DateTimeHelper";
import { errorMessageInArray } from "../../../../../common/Helper/Support/FormHelper";
import { numberFormatter } from "../../../../../common/Helper/Support/SettingsHelper";

export default {
    name: "LeaveCreateEditModal",
    mixins: [ModalMixin, FormHelperMixins],
    props: {
        tableId: {},
        employee: {},
        specificId: {},
    },
    data() {
        return {
            numberFormatter,
            errorMessageInArray,
            modalPreloader: false,
            ariaExpanded: false,
            formData: {
                job_assignor_id: this.$optional(window.user, 'id'),
                status_id: '22',
            },
            attachments: [],
            availabilities: [],
        }
    },
    methods: {
        submit() {
            this.loading = true;
            let formData = { ...this.formData }

            formData.start_date = formatDateForServer(formData.start_date);
            formData.end_date = formatDateForServer(formData.end_date);

            formData = formDataAssigner(new FormData, formData);

            this.attachments.forEach(file => {
                formData.append('attachments[]', file)
            })

            axiosPost(this.apiUrl.ASSIGN_TASK, formData).then(({ data }) => {
                this.loading = false;
                this.$toastr.s('', data.message);
                $('#assign-task-create-edit-modal').modal('hide');
                this.showModal = false;
                if (this.tableId) {
                    this.$hub.$emit(`reload-${this.tableId}`);
                } else {
                    this.$emit('reload')
                }
            }).catch(({ response }) => {
                this.loading = false;
                this.message = '';
                this.errors = response.data.errors || {};
                this.fieldStatus.isSubmit = true
                if (response.status != 422)
                    this.$toastr.e(response.data.message || response.statusText)
            })
        },
        getEmployeeExistingLeaves() {
            this.modalPreloader = true;
            axiosGet(`${this.apiUrl.LEAVES}/${this.formData.employee_id}/allowances`).then(response => {
                this.availabilities = response.data.allowances;
            }).finally(() => this.modalPreloader = false)
        }
    },
    created() {
        if (!this.employee && !this.assignPermissions && !this.specificId) {
            this.formData.employee_id = window.user.id
            this.getEmployeeExistingLeaves()
        }

        if (this.specificId) {
            this.formData.employee_id = this.specificId
            this.getEmployeeExistingLeaves()
        }
    },
    computed: {
        assignPermissions() {
            if (this.specificId && this.$can('assigned_task')) {
                return false;
            }

            if (this.employee && this.$can('assigned_task')) {
                this.formData.employee_id = this.employee.id
                this.getEmployeeExistingLeaves();
                return false;
            }
            return this.$can('assigned_task');
        },
        employeeId() {
            return this.formData.employee_id;
        },
        selectableLeaveType() {
            let leave_type = [];
            leave_type = addChooseInSelectArray(leave_type, 'name', this.$t('leave_type'));
            if (this.availabilities.length !== 0) {
                this.availabilities.map((availability) => leave_type.push(availability.leave_type))
            }
            return leave_type;
        },
        modalTitle() {
            return this.$can('assigned_task') ? this.$t('add_assign_task') : this.$t('assigned_task');
        }
    },
    watch: {
        employeeId: {
            handler: function (employee_id) {
                if (employee_id && this.assignPermissions) {
                    this.formData.job_assignor_id = '';
                    this.getEmployeeExistingLeaves()
                }
            }
        }
    }
}
</script> -->

<template>
    <modal id="assign-task-create-edit-modal" 
        v-model="showModal" 
        :title="generateModalTitle('assigned_task')" 
        @submit="submitData"
        :loading="loading" :preloader="preloader">
        <form 
            :data-url='selectedUrl ? selectedUrl : apiUrl.ASSIGN_TASK' 
            method="POST" ref="form">
            <app-form-group 
                :label="$t('title')" type="text" v-model="formData.name" :placeholder="$placeholder('title')"
                :required="true" :error-message="$errorMessage(errors, 'name')" />

            <app-form-group-selectable  type="search-select" v-model="formData.user_id"
                    :label="$t('employee')" list-value-field="full_name" :required="true"
                    :error-message="$errorMessage(errors, 'employee_id')" :placeholder="$t('search_and_select_an_employee')"
                    :fetch-url="`${apiUrl.TENANT_SELECTABLE_USER}?without=employee=only&with_auth=yes`" />
            <div class="row">
                <div class="col-md-6">
                    <app-form-group type="date" v-model="formData.start_date" :label="$t('start_date')"
                        :required="true" :error-message="$errorMessage(errors, 'start_date')"
                        :placeholder="$t('enter_start_date')" />
                </div>
                <div class="col-md-6">
                    <app-form-group type="date" v-model="formData.end_date" :label="$t('end_date')" :required="true"
                        :error-message="$errorMessage(errors, 'end_date')" :placeholder="$t('enter_end_date')" />
                </div>
            </div> 
            <app-form-group type="textarea" :text-area-rows="4" v-model="formData.description"
                :label="$t('desc_work')" :error-message="$errorMessage(errors, 'note')"
                :placeholder="$t('add_reason_note_here')" />
        </form>
    </modal>
</template>

<script>
import ModalMixin from "../../../../../common/Mixin/Global/ModalMixin";
import FormHelperMixins from "../../../../../common/Mixin/Global/FormHelperMixins";
import { axiosPost } from "../../../../../common/Helper/AxiosHelper";
import { formDataAssigner } from "../../../../../common/Helper/Support/FormHelper";
import { formatDateForServer } from "../../../../../common/Helper/Support/DateTimeHelper";
import { errorMessageInArray } from "../../../../../common/Helper/Support/FormHelper";
import { numberFormatter } from "../../../../../common/Helper/Support/SettingsHelper";

export default {
    name: "LeaveCreateEditModal",
    mixins: [FormHelperMixins, ModalMixin],
    props: {
        tableId: {},
        employee: {},
        specificId: {},
    },
    data() {
        return {
            numberFormatter,
            errorMessageInArray,
            modalPreloader: false,
            ariaExpanded: false,
            formData: {
                job_assignor_id: this.$optional(window.user, 'id'),
                status_id: '22',
            },
            attachments: [],
            availabilities: [],
        }
    },
    computed: {
        parentDeptEditPermission() {
            return this.selectedUrl ? this.formData.status_id : true;
        }
    },
    methods: {
        submitData() {
            this.loading = true;
            let formData = { ...this.formData }

            formData.start_date = formatDateForServer(formData.start_date);
            formData.end_date = formatDateForServer(formData.end_date);

            formData = formDataAssigner(new FormData, formData);

            this.attachments.forEach(file => {
                formData.append('attachments[]', file)
            })

            axiosPost(this.apiUrl.ASSIGN_TASK, formData).then(({ data }) => {
                this.loading = false;
                this.$toastr.s('', data.message);
                $('#assign-task-create-edit-modal').modal('hide');
                this.showModal = false;
                if (this.tableId) {
                    this.$hub.$emit(`reload-${this.tableId}`);
                } else {
                    this.$emit('reload')
                }
            }).catch(({ response }) => {
                this.loading = false;
                this.message = '';
                this.errors = response.data.errors || {};
                this.fieldStatus.isSubmit = true
                if (response.status != 422)
                    this.$toastr.e(response.data.message || response.statusText)
            })
        },
        afterSuccess({ data }) {
            this.formData = {};
            $('#assign-task-create-edit-modal').modal('hide');
            this.toastAndReload(data.message, 'assign-task-table')
            this.$emit('input', false);
        },
        afterSuccessFromGetEditData({ data }) {
            this.preloader = false
            this.formData = data
            if (this.formData.status_id === null) {
                this.formData.status_id = ''
            }
            if (this.formData.manager_id === null) {
                this.formData.manager_id = ''
            }
        }
    }
}
</script>
