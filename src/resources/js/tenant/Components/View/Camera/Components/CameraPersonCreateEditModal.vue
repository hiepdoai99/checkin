<template>
    <modal id="camera-place-modal"
           v-model="showModal"
           :title="generateModalTitle('cameras_person_emp')"
           @submit="submitData"
           :loading="loading"
           :preloader="preloader">
        <form ref="form" :data-url='this.selectedUrl ? this.selectedUrl : apiUrl.CAMERA_PLACES'>
            <app-form-group
                :label="$t('name')"
                v-model="formData.name"
                :placeholder="$placeholder('name', '')"
                :required="true"
                :error-message="$errorMessage(errors, 'name')"
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

export default {
    name: "CameraPersonCreateEditModal",
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
            this.toastAndReload(data.message, 'camera-person-table');
            this.formData = {};
            $('#camera-person-modal').modal('hide');
            this.$emit('input', false);
        }
    },
}
</script>

<style scoped>

</style>
