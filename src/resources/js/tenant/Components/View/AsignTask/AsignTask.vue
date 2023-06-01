<template>
  <div class="content-wrapper bg-white">
    <app-page-top-section :title="$t('assigned_task')">
      <app-default-button
      v-if="$can('create_assign_task')"
                  :title="$addLabel('assigned_task')"
                  @click="openLeaveModal()"
              />
    </app-page-top-section>
    <app-table 
      :id="tableId" 
      :options="options" 
      @action="triggerActions" 
      @getFilteredValues="getFilterValues" 
    />
    <app-asign-task-edit-create-modal 
      v-if="isLeaveModalActive" 
      v-model="isLeaveModalActive" 
      :tableId="tableId"
      :specificId="adminRequestId"
      :selected-url="selectedUrl"
    />
  </div>
</template>

<script>
import AsignTaskMixin from "../../Mixins/AsignTaskMixin";
import LeaveRequestActionMixin from "../../Mixins/LeaveRequestActionMixin";
import AsignTaskTopButtons from "./Components/AsignTaskTopButtons";
import AsignTaskContextMenu from "./Components/AsignTaskContextMenu";
import { ASSIGN_TASK } from "../../../Config/ApiUrl";

export default {
  name: "AssignTask",
  mixins: [AsignTaskMixin, LeaveRequestActionMixin],
  components: { AsignTaskTopButtons, AsignTaskContextMenu },
  props: {
    assignTaskId: {},
    managerDept: {}
  },
  data() {
    return {
      selectedUrl: '',
      isLeaveModalActive: false,
    }
  },
  created() {
    if (this.assignTaskId) {
      this.logUrl = `${ASSIGN_TASK}/${this.assignTaskId}`
      this.isResponseLogModalActive = true;
    }
  },
  watch: {
    isLeaveModalActive: function (value) {
      if (!value) {
        this.selectedUrl = '';
      }
    }
  },
  methods: {
    openLeaveModal() {
      this.selectedUrl = '';
      this.isLeaveModalActive = true;
    },
    triggerActions(row, action, active) {

      if (action.name === 'edit') {
        this.selectedUrl = `${action.url}/${row.id}`;
        this.isLeaveModalActive = true;
      } else {
        this.getAction(row, action, active)
      }
    }
  }
}
</script>