<template>
    <modal id="work-shift-modal"
           size="large"
           v-model="showModal"
           :title="$fieldTitle(buttonFirstTitle, 'register_work_shift', true)"
           @submit="submitData"
           :scrollable="false"
           :loading="loading"
           :preloader="preloader">
        <form  class="row"
            ref="form"
            @submit.prevent="submitData"
        >
            <div class="col-12">
                <app-form-group-selectable
                    :label="$t('employee')"
                    type="search-select"
                    v-if="canUpdateStatus"
                    v-model="formData.employee_id"
                    :required="true"
                    list-value-field="full_name"
                    :placeholder="$t('search_and_select_an_employee')"
                    :error-message="$errorMessage(errors, 'employee_id')"
                    :fetch-url="`${apiUrl.TENANT_SELECTABLE_USER}?without=admin&employee=only&with_auth=yes`"
                />
            </div>

            <div class="col-6">
                <app-form-group
                    :label="$t('work_shifts')"
                    type="select"
                    date-mode="select"
                    v-model="formData.work_shifts"
                    :placeholder="$placeholder('work_shifts')"
                    :required="true"
                    :error-message="$errorMessage(errors, 'work_shifts')"
                />
            </div>

            <div class="col-6">
                <app-form-group
                    :label="$t('date')"
                    type="date"
                    date-mode="date"
                    v-model="formData.date"
                    :placeholder="$placeholder('date')"
                    :required="true"
                    :error-message="$errorMessage(errors, 'date')"
                />
            </div>
            
            <div class="col-12">
                <app-form-group
                    :label="$t('reason_note_for_manual_entry')"
                    type="textarea"
                    form-group-class="mb-0"
                    :text-area-rows="4"
                    v-model="formData.note"
                    :placeholder="$placeholder('note')"
                    :error-message="$errorMessage(errors, 'note')"
                />
            </div>
        </form>
    </modal>
</template>

<script>
import ModalMixin from "../../../../../common/Mixin/Global/ModalMixin";
import FormHelperMixins from "../../../../../common/Mixin/Global/FormHelperMixins";
import {formatDateTimeForServer, formatDateForServer} from "../../../../../common/Helper/Support/DateTimeHelper";

export default {
    name: "AttendanceCreateEditModal",
    mixins: [ModalMixin, FormHelperMixins],
    props: {
        tableId: {},
        employee: {},
        specificId: {}
    },
    data() {
        return {
            formData: {},
        }
    },
    mounted() {
        this.$nextTick(()=>{
            if (window.innerHeight < 700){
                document.getElementsByClassName('modal-body')[0].style.height = '560px'
            }
        })
    },
    methods: {
        submitData() {
            this.loading = true;
            const formData = {...this.formData};
            formData.in_date = formatDateForServer(this.formData.in_time);
            formData.in_time = formatDateTimeForServer(this.formData.in_time);
            formData.out_time = formatDateTimeForServer(this.formData.out_time);

            this.submitFromFixin(
                'post',
                `${this.apiUrl.EMPLOYEES}/add-attendance`,
                formData
            );
        },
        afterSuccess({data}) {
            this.loading = false;
            $('#attendance-modal').modal('hide');
            this.$toastr.s('', data.message);
            if (this.tableId) {
                this.$hub.$emit(`reload-${this.tableId}`);
            } else {
                this.$emit('reload')
            }
        },
    },
    created() {
        if (!this.employee && !this.canUpdateStatus && !this.specificId) {
            this.formData.employee_id = window.user.id
        }

        if (this.specificId) {
            this.formData.employee_id = this.specificId
        }
    },
    computed: {
        canUpdateStatus() {
            if (this.specificId && this.$can('update_attendance_status')) {
                return false;
            }
            if (this.employee && this.$can('update_attendance_status')) {
                this.formData.employee_id = this.employee.id
                return false;
            }
            return this.$can('update_attendance_status');
        },
        user_id() {
            return window.user.id;
        },
        buttonFirstTitle() {
            return this.$can('update_attendance_status') ? 'add' : 'request';
        }
    }
}
</script>