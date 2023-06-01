<template>
    <modal id="camera-api-modal"
           v-model="showModal"
           :title="generateModalTitle('camera_api_setting')"
           @submit="submitData"
           :loading="loading"
           :preloader="preloader">
        <form ref="form" :data-url='this.selectedUrl ? this.selectedUrl : apiUrl.CAMERA_HANETS'>
            <app-form-group
                :label="$t('email')"
                v-model="formData.email"
                :placeholder="$placeholder('email', '')"
                :required="true"
                :error-message="$errorMessage(errors, 'email')"
            />

            <app-form-group
                :label="$t('client_id')"
                v-model="formData.client_id"
                :placeholder="$placeholder('client_id', '')"
                :required="true"
                :error-message="$errorMessage(errors, 'client_id')"
            />

            <app-form-group
                :label="$t('client_secret')"
                type="password"
                v-model="formData.client_secret"
                :placeholder="$placeholder('client_secret', '')"
                :required="true"
                :show-password="true"
                :error-message="$errorMessage(errors, 'client_secret')"
            />

            <app-form-group
                :label="$t('access_token')"
                type="password"
                v-model="formData.access_token"
                :placeholder="$placeholder('access_token', '')"
                :required="false"
                :show-password="true"
                :error-message="$errorMessage(errors, 'access_token')"
            />

            <app-form-group
                :label="$t('refresh_token')"
                type="password"
                v-model="formData.refresh_token"
                :placeholder="$placeholder('refresh_token', '')"
                :required="false"
                :show-password="true"
                :error-message="$errorMessage(errors, 'refresh_token')"
            />

        </form>
    </modal>
</template>

<script>
import ModalMixin from "../../../../../common/Mixin/Global/ModalMixin";
import FormHelperMixins from "../../../../../common/Mixin/Global/FormHelperMixins";

export default {
    name: "CameraApiCreateEditModal",
    mixins: [ModalMixin, FormHelperMixins],
    data() {
        return {
            formData: {
                is_enabled: true,
                is_earning_enabled: true,
            }
        }
    },
    methods: {
        afterSuccess({data}) {
            this.toastAndReload(data.message, 'camera-apis-table');
            this.formData = {};
            $('#camera-api-modal').modal('hide');
            this.$emit('input', false);
        }
    },
}
</script>

<style scoped>

</style>
