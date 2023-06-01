<template>
    <modal id="camera-place-modal"
           v-model="showModal"
           :title="generateModalTitle('cameras_place')"
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
                :label="$t('address')"
                v-model="formData.address"
                :placeholder="$placeholder('address', '')"
                :required="true"
                :error-message="$errorMessage(errors, 'address')"
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
    name: "CameraPlaceCreateEditModal",
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
            this.toastAndReload(data.message, 'camera-place-table');
            this.formData = {};
            $('#camera-place-modal').modal('hide');
            this.$emit('input', false);
        }
    },
}
</script>

<style scoped>

</style>
