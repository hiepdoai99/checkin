import DatatableHelperMixin from "../../../common/Mixin/Global/DatatableHelperMixin";
import { REPORT_EMPLOYEE } from "../../Config/ApiUrl";

export default {
    mixins: [DatatableHelperMixin],
    data() {
        return {
            options: {
                name: this.$t('listLate'),
                url: REPORT_EMPLOYEE +'?page=1&within=lastMonth&month=Thg%2001',
                showHeader: true,
                showCount: false,
                showClearFilter: true,
                showSearch: false,
                columns: [
                    // {
                    //     title: this.$t('no'),
                    //     type: 'text',
                    //     key: 'data',
                    //     isVisible: true,
                    //     modifier: ( item,index) => {
                    //         return item.length() > 0 ? index : '' ;
                    //     }
                    // },
                    {
                        title: this.$t('view_employees'),
                        type: 'custom-html',
                        key: 'full_name',
                        modifier: (full_name) => {
                            return full_name;
                        }
                    },
                    {
                        title: this.$t('time') + '(' + this.$t('minutes') + ')',
                        type: 'custom-html',
                        key: 'in_time_late',
                        isVisible: true,
                        modifier: (item) => {
                            return item ? item : '-';
                        }
                    },
                    {
                        title: this.$t('departments'),
                        type: 'custom-html',
                        key: 'department',
                        isVisible: true,
                        modifier: (department) => {
                            return department ? this.$optional(department, 'name') : '-';
                        }
                    },
                    {
                        title: this.$t('branch'),
                        type: 'custom-html',
                        key: 'parent_department',
                        modifier: (branch) => {
                            return branch ? this.$optional(branch, 'name') : '-';
                        }
                    },
                    {
                        title: this.$t('position'),
                        type: 'custom-html',
                        key: 'roles',
                        isVisible: true,
                        modifier: (roles) => {
                            return roles ? this.$optional(roles, 'name') : '-';
                        }
                    },
                ],
                filters: [
                    {
                        title: this.$t('date'),
                        type: "range-picker",
                        key: "date",
                        option: ["today", "thisMonth", "last7Days", "thisYear"]
                    },
                ],
                paginationType: "pagination",
                responsive: true,
                rowLimit: 10,
                showAction: true,
                orderBy: 'asc',
                actionType: "dropdown",
            }
        }
    }
}
