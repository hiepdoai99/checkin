<template>
    <div class="content-wrapper bg-white">
        <app-page-top-section :title="$t('camera_settings')"/>
        <app-tab
            :tabs="tabs"
            icon="settings"
        />
    </div>
</template>

<script>
export default {
    name: "CameraSettingLayout",
    data() {
        return {
            tabs: [
                {
                    headerButton: {
                        "label": this.$fieldTitle('add', 'camera_api_setting', true),
                    },
                    name: this.$t('camera_api_setting'),
                    title: this.$t('camera_api_setting'),
                    component: "app-camera-api-setting",
                    permission: this.$can('view_camera_settings')
                }
            ]
        }
    }
}
</script>

<style scoped>

</style>