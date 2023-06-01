<template>
    <modal id="camera-person-sync-modal"
           v-model="showModal"
           :title="generateModalTitle('sync_camera')"
           @submit="submitData"
           :loading="loading"
           :preloader="preloader">
        <form ref="form" :data-url='this.selectedUrl ? `${this.selectedUrl}/sync` : apiUrl.CAMERA_PLACES'>
            <app-form-group
                :label="$t('name')"
                v-model="formData.name"
                :placeholder="$placeholder('name', '')"
                :required="true"
                :error-message="$errorMessage(errors, 'name')"
            />

            <app-form-group-selectable
                type="search-select"
                :label="$t('employee')"
                list-value-field="email"
                :chooseLabel="$t('employee')"
                v-model="formData.aliasID"
                :required="true"
                :error-message="$errorMessage(errors, 'aliasID')"
                :fetch-url="users_url"
            />

            <app-form-group
                :label="$t('placeID')"
                v-model="formData.placeID"
                :placeholder="$placeholder('placeID', '')"
                :required="false"
                :disabled="true"
                :error-message="$errorMessage(errors, 'placeID')"
            />
        </form>
    </modal>
</template>

<script>
import ModalMixin from "../../../../../common/Mixin/Global/ModalMixin";
import FormHelperMixins from "../../../../../common/Mixin/Global/FormHelperMixins";
import {TENANT_SELECTABLE_ROLE_USER} from '../../../../../common/Config/apiUrl'


export default {
    name: "CameraPersonSyncModal",
    mixins: [ModalMixin, FormHelperMixins],
    data() {
        return {
            formData: {
                is_enabled: true,
                is_earning_enabled: true,
            },
            users_url: TENANT_SELECTABLE_ROLE_USER,
        }
    },
    methods: {
        afterSuccess({data}) {
            this.toastAndReload(data.message, 'camera-person-table');
            this.formData = {};
            $('#camera-person-sync-modal').modal('hide');
            this.$emit('input', false);
        }
    },
}
</script>

<style scoped>

</style>
