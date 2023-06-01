<template>
    <div class="content-wrapper bg-white">
        <app-page-top-section :title="$t('register_work_shift')">
            <app-work-shift-top-buttons :tableId="id" :request-button="true"/>
        </app-page-top-section>

        <app-table
            :id="id"
            :options="options"
            @action="triggerActions"
            @getRows="getSelectedRows"
            @getFilteredValues="getFilterValues"
        />

        <app-attendance-edit-request-modal
            v-if="isEditModalActive"
            v-model="isEditModalActive"
            :selectedUrl="selectedUrl"
            :tableId="id"
        />

        <app-confirmation-modal
            v-if="confirmationModalActive"
            :message="modalSubtitle"
            :modal-class="modalClass"
            :icon="modalIcon"
            modal-id="app-confirmation-modal"
            @confirmed="updateStatus()"
            @cancelled="cancelled"
        />

        <app-attendance-log-modal
            v-if="isAttendanceLogModalActive"
            v-model="isAttendanceLogModalActive"
            :url="changeLogUrl"
            :tableId="id"
        />

        <attendance-request-context-menu
            v-if="isContextMenuOpen"
            :requests="selectedRequests"
            :all-selected="allSelected"
            @reload="afterBulkAction"
        />
    </div>
</template>

<script>
import WorkShiftMixin from "../../Mixins/WorkShiftMixin";
import AttendanceRequestActionMixin from "../../Mixins/AttendanceRequestActionMixin";
import AppWorkShiftTopButtons from "./Component/AppWorkShiftTopButtons";
import AttendanceRequestContextMenu from "./Component/AttendanceRequest/AttendanceRequestContextMenu";

export default {
    mixins: [WorkShiftMixin, AttendanceRequestActionMixin],
    components: { AppWorkShiftTopButtons, AttendanceRequestContextMenu},
    props: {
        detailsId: {},
        attendanceId: {},
    },
    data() {
        return {
            id: 'business-trip-table',
            isAttendanceModalActive: false,
            selectedUrl: '',
        }
    },
    created() {
        if (this.detailsId) {
            this.changeLogUrl = `${this.apiUrl.ATTENDANCES}/${this.detailsId}/log`;
            this.isAttendanceLogModalActive = true;
        }
    }
}
</script>