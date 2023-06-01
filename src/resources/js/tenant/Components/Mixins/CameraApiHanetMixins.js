import {CAMERA_HANETS} from '../../Config/ApiUrl'

export default {
    data() {
        return {
            options: {
                name: this.$t('camera_api_setting'),
                url: CAMERA_HANETS,
                showHeader: true,
                tableShadow:false,
                tablePaddingClass:'pt-primary',
                columns: [
                    {
                        title: this.$t('email'),
                        type: 'text',
                        key: 'email',
                        isVisible: true,
                    },
                    {
                        title: this.$t('client_id'),
                        type: 'text',
                        key: 'client_id',
                    },
                    {
                        title: this.$t('client_secret'),
                        type: 'custom-html',
                        key: 'client_secret',
                        modifier: client_secret => client_secret ? client_secret.substring(0, 15) + "..." : '-'
                    },
                    {
                        title: this.$t('access_token'),
                        type: 'custom-html',
                        key: 'access_token',
                        modifier: access_token => access_token ? access_token.substring(0, 15) + "..." : '-'
                    },
                    {
                        title: this.$t('actions'),
                        type: 'action',
                        isVisible: true
                    },
                ],
                filters: [
                   
                ],
                paginationType: "pagination",
                responsive: true,
                rowLimit: 10,
                showAction: true,
                orderBy: 'desc',
                actionType: "default",
                actions: [
                    {
                        title: this.$t('edit'),
                        icon: 'edit',
                        type: 'modal',
                        component: 'app-camera-api-create-edit',
                        modalId: 'camera-api-modal',
                        url: CAMERA_HANETS,
                        name: 'edit',
                        modifier: row => this.$can('create_cameras')
                    },
                    {
                        title: this.$t('delete'),
                        name: 'delete',
                        icon: 'trash-2',
                        modalClass: 'warning',
                        url: CAMERA_HANETS,
                        modifier: row => this.$can('delete_cameras')
                    },
                ],
            }
        }
    }
}
