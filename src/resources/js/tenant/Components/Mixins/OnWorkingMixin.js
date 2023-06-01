import DatatableHelperMixin from "../../../common/Mixin/Global/DatatableHelperMixin";
import {REPORT_EMPLOYEE} from "../../Config/ApiUrl";

export default {
    mixins: [DatatableHelperMixin],
    data() {
        return {
            options: {
                name: this.$t('OnWorkingToday'),
                url: REPORT_EMPLOYEE +'?within=lastMonth',
                showHeader: false,
                showCount: false,
                showClearFilter: true,
                showSearch: false,
                columns: [
                    {
                        title: this.$t('no'),
                        type: 'text',
                        key: 'id',
                    },
                    {
                        title: this.$t('name'),
                        type: 'custom-html',
                        key: 'full_name',
                    },
                    {
                        title: this.$t('minutes_late'),
                        type: 'custom-html',
                        key: 'out_time_early',
                    },
                    {
                        title: this.$t('total_work'),
                        type: 'custom-html',
                        key: 'work',
                    },
                ],
                paginationType: "pagination",
                responsive: false,
                rowLimit: 10,
                showAction: false,
                orderBy: 'asc',
            }
        }
    }
}
