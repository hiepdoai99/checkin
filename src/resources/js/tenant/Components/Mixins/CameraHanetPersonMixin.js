import DatatableHelperMixin from "../../../common/Mixin/Global/DatatableHelperMixin";
import {CAMERA_PERSONS} from "../../Config/ApiUrl";

export default {
    mixins: [DatatableHelperMixin],
    data() {
        return {
            options: {
                name: this.$t('cameras_person_emp'),
                url: CAMERA_PERSONS,
                showHeader: true,
                showCount: true,
                showClearFilter: true,
                columns: [
                    {
                        title: this.$t('avatar'),
                        type: 'custom-html',
                        key: 'avatar',
                        isVisible: true,
                        modifier: (avatar) => {
                            return  avatar ?
                                `<div class="avatars-w-40" style="height: 80px;background: url('${avatar}') no-repeat;background-size: cover;background-position: center center;" ></div>` :
                                '<div class="avatars-w-40"><img src="/images/avatar.png" alt="image" class="rounded-circle"></div>';
                        }
                    },
                    {
                        title: this.$t('name'),
                        type: 'text',
                        key: 'name',
                        isVisible: true,
                    },
                    {
                        title: this.$t('employee'),
                        type: 'custom-html',
                        key: 'aliasID',
                        modifier: (aliasID) => {
                            return  aliasID ?
                                `<span class="badge badge-pill badge-info">${this.$t('synced_camera')}</span>` :
                                `<span class="badge badge-pill badge-warning">${this.$t('not_sync_camera')}</span>`;
                        }
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
                        title: this.$t('sync_camera'),
                        icon: 'send',
                        type: 'modal',
                        component: 'app-camera-person-sync',
                        modalId: 'camera-person-sync-modal',
                        url: CAMERA_PERSONS,
                        name: 'sync',
                        modifier: row => this.$can('update_cameras')
                    },
                    {
                        title: this.$t('edit'),
                        icon: 'edit',
                        type: 'modal',
                        component: 'app-camera-person-create-edit',
                        modalId: 'camera-person-modal',
                        url: CAMERA_PERSONS,
                        name: 'edit',
                        modifier: row => this.$can('update_cameras')
                    },
                ],
            }
        }
    }
}
