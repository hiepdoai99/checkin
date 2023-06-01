<template>
    <div class="content-wrapper bg-white">
        <app-page-top-section :title="$t('cameras_person_emp')" icon="briefcase">
            <!-- <app-default-button
                v-if="$can('create_cameras')"
                :title="$addLabel('place')"
                @click="openPlaceModal()"
            /> -->
        </app-page-top-section>

        <app-table
            id="camera-person-table"
            :options="options"
            @action="triggerActions"
        />

        <app-camera-person-sync
            v-if="isPersonSyncModalActive"
            v-model="isPersonSyncModalActive"
            :selected-url="selectedUrl"
        />

        <app-camera-person-create-edit
            v-if="isPersonModalActive"
            v-model="isPersonModalActive"
            :selected-url="selectedUrl"
        />

    </div>
</template>

<script>
import CameraHanetPersonMixin from "../../Mixins/CameraHanetPersonMixin";
import {axiosPost} from "../../../../common/Helper/AxiosHelper";

export default {
    name: "CamerasPerson",
    mixins: [CameraHanetPersonMixin],
    data() {
        return {
            selectedUrl: '',
            isPersonSyncModalActive: false,
            isPersonModalActive: false,
            employee: {},
        }
    },
    watch: {
        isPersonSyncModalActive: function (value) {
            if (!value) {
                this.selectedUrl = '';
            }
        },
        isPersonModalActive: function (value) {
            if (!value) {
                this.selectedUrl = '';
            }
        }
    },
    methods: {
        openPlaceModal() {
            this.selectedUrl = '';
            this.isPersonModalActive = true;
        },
        triggerActions(row, action, active) {
            if (action.name === 'edit') {
                this.selectedUrl = `${action.url}/${row.personID}`;
                this.isPersonModalActive = true;
            } else if (action.name === 'sync') {
                this.selectedUrl = `${action.url}/${row.personID}`;
                this.isPersonSyncModalActive = true;
            } else {
                this.getAction(row, action, active)
            }
        }
    }
}
</script>
