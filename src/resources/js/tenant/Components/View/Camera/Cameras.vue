<template>
    <div class="content-wrapper bg-white">
        <app-page-top-section :title="$t('cameras')" icon="briefcase">
            <app-default-button
                v-if="$can('create_cameras')"
                :title="$addLabel('camera')"
                @click="openDepartmentModal()"
            />
        </app-page-top-section>

        <app-table
            id="camera-table"
            :options="options"
            @action="triggerActions"
        />

        <app-camera-modal
            v-if="isDepartmentModalActive"
            v-model="isDepartmentModalActive"
            :selected-url="selectedUrl"
        />

        <app-confirmation-modal
            :message="promptSubtitle"
            :modal-class="modalClass"
            :icon="promptIcon"
            v-if="confirmationModalActive"
            modal-id="app-confirmation-modal"
            @confirmed="changeStatus"
            @cancelled="cancelled"
        />

    </div>
</template>

<script>
import CameraDeviceMixin from "../../Mixins/CameraDeviceMixin";
import {axiosPost} from "../../../../common/Helper/AxiosHelper";

export default {
    name: "Cameras",
    mixins: [CameraDeviceMixin],
    data() {
        return {
            selectedUrl: '',
            isDepartmentModalActive: false,
            confirmationModalActive: false,
            isEmployeeMovementModalActive: false,
        }
    },
    created() {
        this.$store.dispatch('getStatuses', 'department')
    },
    watch: {
        isDepartmentModalActive: function (value) {
            if (!value) {
                this.selectedUrl = '';
            }
        }
    },
    methods: {
        openDepartmentModal() {
            this.selectedUrl = '';
            this.isDepartmentModalActive = true;
        },
        triggerActions(row, action, active) {
            this.departmentId = row.id;
            this.promptIcon = action.icon;
            this.modalClass = action.modalClass;
            this.promptSubtitle = action.promptSubtitle;

            if (action.name === 'edit') {
                this.selectedUrl = `${action.url}/${row.id}`;
                this.isDepartmentModalActive = true;
            } else if (['deactivate', 'activate'].includes(action.key)) {
                this.confirmationModalActive = true;
            } else if (action.name === 'move-employee') {
                this.departmentId = row.id;
                this.departmentName = row.name;
                this.isEmployeeMovementModalActive = true;
            } else {
                this.getAction(row, action, active)
            }
        }
    }
}
</script>
