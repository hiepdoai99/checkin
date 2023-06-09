<template>
    <div class="content-wrapper bg-white">
        <app-page-top-section :title="$t('all_employees')" icon="briefcase">
            <app-default-button
                @click="addEmployee()"
                :title="$fieldTitle('add', 'employee', true)"/>
            <app-default-button
                v-if="$can('invite_employees')"
                @click="isModalActive = true"
                :title="$fieldTitle('invite', 'employee', true)"/>
        </app-page-top-section>

        <app-table
            id="employee-table"
            :options="options"
            :card-view="true"
            @getRows="getSelectedRows"
            @action="triggerActions"
        />

        <app-employee-invite
            v-if="isModalActive"
            v-model="isModalActive"
            :selected-url="selectedUrl"
        />
        
        <app-employee-add 
            v-if="isAddEmployeeModalActive"
            v-model="isAddEmployeeModalActive"
            />

        <app-confirmation-modal
            :title="promptTitle"
            :message="promptMessage"
            :modal-class="modalClass"
            :icon="promptIcon"
            v-if="confirmationModalActive"
            modal-id="app-confirmation-modal"
            @confirmed="triggerConfirm"
            @cancelled="cancelled"
            :loading="loading"
            :self-close="false"
        />

        <app-employment-status-modal
            v-if="employmentStatusModalActive"
            v-model="employmentStatusModalActive"
            :id="employeeId"
        />

        <app-employee-termination-reason-modal
            v-if="isTerminationReasonModalActive"
            v-model="isTerminationReasonModalActive"
            :id="employeeId"
        />

        <employee-context-menu
            v-if="isContextMenuOpen"
            :employees="selectedEmployees"
            @close="isContextMenuOpen = false"
        />

        <app-attendance-create-edit-modal
            v-if="attendanceModalActive"
            v-model="attendanceModalActive"
            :employee="employee"
        />

        <app-leave-create-edit-modal
            v-if="leaveModalActive"
            v-model="leaveModalActive"
            :employee="employee"
        />

        <job-history-edit-modal
            v-if="isJobHistoryEditModalActive"
            v-model="isJobHistoryEditModalActive"
            :modalType="modalAction"
            :employee="employee"
            @reload="reloadEmployeeTable"
        />

    </div>
</template>

<script>
import EmployeeMixin from "../../Mixins/EmployeeMixin";
import EmployeeContextMenu from "./Components/EmployeeContextMenu";
import JobHistoryEditModal from "./Components/JobHistory/components/JobHistoryEditModal";
import {axiosGet} from "../../../../common/Helper/AxiosHelper";

export default {
    components: {EmployeeContextMenu, JobHistoryEditModal},
    mixins: [EmployeeMixin],
    mounted() {
        this.getSalaryRange();
    },
    data() {
        return {
            isAddEmployeeModalActive: false,
        }
    },
    methods:{
        getSalaryRange() {
            axiosGet(this.apiUrl.SALARY_RANGE).then(({data}) => {
                let salaryFilter = this.options.filters.find(item => item.key === 'salary');
                salaryFilter.maxRange = data.max_salary;
                salaryFilter.minRange = data.min_salary < data.max_salary ? data.min_salary : 0;
            })
        },

        addEmployee() {
            this.isAddEmployeeModalActive = true;
        }
    }
}
</script>
