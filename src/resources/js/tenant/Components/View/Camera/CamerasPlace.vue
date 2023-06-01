<template>
    <div class="content-wrapper bg-white">
        <app-page-top-section :title="$t('places')" icon="briefcase">
            <app-default-button
                v-if="$can('create_cameras')"
                :title="$addLabel('place')"
                @click="openPlaceModal()"
            />
        </app-page-top-section>

        <app-table
            id="camera-place-table"
            :options="options"
            @action="triggerActions"
        />

        <app-camera-place-create-edit
            v-if="isPlaceModalActive"
            v-model="isPlaceModalActive"
            :selected-url="selectedUrl"
        />

    </div>
</template>

<script>
import CameraHanetPlaceMixin from "../../Mixins/CameraHanetPlaceMixin";
import {axiosPost} from "../../../../common/Helper/AxiosHelper";

export default {
    name: "CamerasPlace",
    mixins: [CameraHanetPlaceMixin],
    data() {
        return {
            selectedUrl: '',
            isPlaceModalActive: false,
        }
    },
    created() {
        this.$store.dispatch('getStatuses', 'department')
    },
    watch: {
        isPlaceModalActive: function (value) {
            if (!value) {
                this.selectedUrl = '';
            }
        }
    },
    methods: {
        openPlaceModal() {
            this.selectedUrl = '';
            this.isPlaceModalActive = true;
        },
        triggerActions(row, action, active) {
            if (action.name === 'edit') {
                this.selectedUrl = `${action.url}/${row.id}`;
                this.isPlaceModalActive = true;
            } else {
                this.getAction(row, action, active)
            }
        }
    }
}
</script>
