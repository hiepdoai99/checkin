<template>
    <div class="content-wrapper bg-white">
        <app-page-top-section :title="$t('company_branch')">
            <app-default-button
                :title="$fieldTitle('add', 'work_shift', true)"
                v-if="$can('create_working_shifts')"
                @click="openModal()"
            />
        </app-page-top-section>
        
        <div>Chức năng đang phát triển</div>

        <app-table
            id="branch-table"
            :options="options"
            @action="triggerActions"
        />

        <app-working-shift-modal
            v-if="isModalActive"
            v-model="isModalActive"
            :selected-url="selectedUrl"
            @close="isModalActive = false"
        />

        <app-confirmation-modal
            v-if="confirmationModalActive"
            modal-id="app-confirmation-modal"
            @confirmed="confirmed('working-shift-table')"
            @cancelled="cancelled"
            icon="trash-2"
            sub-title=""
            :message="message"
            modal-class="danger"
        />

        <app-employee-to-work-shift
            v-if="isAddEmployeeModalActive"
            v-model="isAddEmployeeModalActive"
            :id="workingShiftId"
        />

    </div>
</template>

<script>

import HelperMixin from "../../../../../common/Mixin/Global/HelperMixin";
import CompanyBranchMixin from "../../../Mixins/CompanyBranchMixin";
import {WORKING_SHIFTS} from "../../../../Config/ApiUrl";

export default {
    name: "companyBranch",
    mixins: [HelperMixin, CompanyBranchMixin],
    data() {
        return {
            isModalActive: false,
            workingShiftId: '',
            selectedUrl: '',
            message: '',
            isAddEmployeeModalActive: false
        }
    },

    methods: {
        openModal() {
            this.selectedUrl = '';
            this.isModalActive = true;
        },
        triggerActions(row, action, active) {
            if (action.name === 'edit') {
                this.selectedUrl = `${WORKING_SHIFTS}/${row.id}`;
                this.isModalActive = true;
            } else if (action.name === 'delete') {
                this.delete_url = `${WORKING_SHIFTS}/${row.id}`;
                this.message = action.message;
                this.confirmationModalActive = true;
            }else if (action.name === 'add-employee') {
                this.isAddEmployeeModalActive = true;
                this.workingShiftId = row.id
            }
        }
    },
}
</script>
