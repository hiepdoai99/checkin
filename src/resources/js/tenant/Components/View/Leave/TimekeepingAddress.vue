<template>
    <div class="content-wrapper bg-white">
        <app-page-top-section :title="$t('timekeeping_by_address')">
            <!-- <timekeeping-address-top-buttons @open-model="openLeaveModal" :request-button="true" /> -->
        </app-page-top-section>
        <div>Chức năng đang phát triển</div>
        <app-table 
            :id="tableId" 
            :options="options" 
            @action="triggerActions"
            @getRows="getSelectedRows"
        />
        <app-leave-create-edit-modal v-if="isLeaveModalActive" v-model="isLeaveModalActive" :tableId="tableId"
            :specificId="adminRequestId" />
        <app-leave-response-log-modal v-if="isResponseLogModalActive" v-model="isResponseLogModalActive" :url="logUrl"
            :table-id="tableId" :manager-dept="managerDept" />
        <app-confirmation-modal v-if="confirmationModalActive" :message="modalSubtitle" :modal-class="modalClass"
            :icon="modalIcon" modal-id="app-confirmation-modal" @confirmed="updateStatus" @cancelled="cancelled" />
        <leave-request-context-menu v-if="isContextMenuOpen" :requests="selectedRequests" :all-selected="allSelected"
            @reload="afterBulkAction" />
    </div>
</template>

<script>
import TimekeepingAddressMixin from "../../Mixins/TimekeepingAddressMixin";
import LeaveRequestActionMixin from "../../Mixins/LeaveRequestActionMixin";
import TimekeepingAddressTopButtons from "./Components/TimekeepingAddressTopButtons.vue";
import LeaveRequestContextMenu from "./Components/LeaveRequestContextMenu";

export default {
    name: "LeaveRequest",
    mixins: [TimekeepingAddressMixin, LeaveRequestActionMixin],
    components: { TimekeepingAddressTopButtons, LeaveRequestContextMenu },
    props: {
        leaveId: {},
        managerDept: {}
    },
    created() {
        if (this.leaveId) {
            this.logUrl = `${this.apiUrl.LEAVES}/${this.leaveId}/log`
            this.isResponseLogModalActive = true;
        }
    }
}
</script>