<template>
    <div>
        <app-note
            class="mb-primary"
            :title="$t('note')"
            content-type="html"
            :notes="`<ol>
                        <li>${$t('need_setting_camera_api_for_auto_checkin')}</li>
                        <li>${$t('how_camera_hanet_settings_work_message',{
                            link: `<a href='${hanet_developer_api}' target='_blank'>${$t('documentation')}</a>`
                        })}</li>
                        <li>${$t('cron_job_setting_warning')}</li>
                    </ol>`"
        />

        <app-table
            id="camera-apis-table"
            :options="options"
            @action="triggerActions"
        />

        <app-camera-api-create-edit
            v-if="isModalActive"
            v-model="isModalActive"
            :selected-url="selectedUrl"
        />

        <app-confirmation-modal
            v-if="confirmationModalActive"
            icon="trash-2"
            modal-id="app-confirmation-modal"
            @confirmed="confirmed('camera-apis-table')"
            @cancelled="cancelled"
        />
    </div>
</template>

<script>

import HelperMixin from "../../../../common/Mixin/Global/HelperMixin";
import CameraApiHanetMixins from "../../Mixins/CameraApiHanetMixins";
import TriggerActionMixin from "../../../../common/Mixin/Global/TriggerActionMixin";
import DeleteMixin from "../../../../common/Mixin/Global/DeleteMixin";
import {HANET_DEVELOPER_API} from "../../../../common/Config/apiUrl";

export default {
    name: "CameraApiSetting",
    mixins: [HelperMixin, CameraApiHanetMixins, TriggerActionMixin, DeleteMixin],
    props: {
        props: {
            default: ''
        },
        id: {
            type: String
        }
    },
    data() {
        return {
            selectedUrl: '',
            isModalActive: false,
            confirmationModalActive: false,
            hanet_developer_api: HANET_DEVELOPER_API,
        }
    },
    mounted() {
        this.$hub.$on('headerButtonClicked-' + this.id, (component) => {
            this.isModalActive = true;
            this.selectedUrl = '';
        })
    },
    methods: {
        triggerActions(row, action, active) {
            if (action.name === 'edit') {
                this.selectedUrl = `${this.apiUrl.CAMERA_HANETS}/${row.id}`;
                this.isModalActive = true;
            } else {
                this.getAction(row, action, active)
            }
        }
    }
}
</script>

<style scoped>

</style>
