import DatatableHelperMixin from "../../../common/Mixin/Global/DatatableHelperMixin";
import {CAMERA_PLACES} from "../../Config/ApiUrl";

export default {
    mixins: [DatatableHelperMixin],
    data() {
        return {
            options: {
                name: this.$t('place'),
                url: CAMERA_PLACES,
                showHeader: true,
                showCount: true,
                showClearFilter: true,
                columns: [
                    {
                        title: this.$t('name'),
                        type: 'text',
                        key: 'name',
                        isVisible: true,
                    },
                    {
                        title: this.$t('address'),
                        type: 'text',
                        key: 'address'
                    },
                    {
                        title: this.$t('placeID'),
                        type: 'text',
                        key: 'placeID',
                        isVisible: true
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
                        component: 'app-camera-place-create-edit',
                        modalId: 'camera-place-modal',
                        url: CAMERA_PLACES,
                        name: 'edit',
                        modifier: row => this.$can('update_cameras')
                    },
                ],
            }
        }
    }
}
