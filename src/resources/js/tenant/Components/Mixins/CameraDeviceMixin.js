import DatatableHelperMixin from "../../../common/Mixin/Global/DatatableHelperMixin";
import {CAMERA_DEVICES} from "../../Config/ApiUrl";

export default {
    mixins: [DatatableHelperMixin],
    data() {
        return {
            options: {
                name: this.$t('camera'),
                url: CAMERA_DEVICES,
                showHeader: true,
                showCount: true,
                showClearFilter: true,
                columns: [
                    {
                        title: this.$t('deviceName'),
                        type: 'text',
                        key: 'deviceName',
                        isVisible: true,
                    },
                    {
                        title: this.$t('placeName'),
                        type: 'text',
                        key: 'placeName',
                        isVisible: true,
                    },
                    {
                        title: this.$t('address'),
                        type: 'text',
                        key: 'address',
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
                        type: 'modal',
                        component: 'app-department-modal',
                        modalId: 'department-modal',
                        url: CAMERA_DEVICES,
                        name: 'edit',
                        modifier: row => this.$can('update_departments')
                    }
                ],
            }
        }
    }
}
