import {DepartmentFilterMixin} from "./FilterMixin";
import HelperMixin from "../../../common/Mixin/Global/HelperMixin";
import DatatableHelperMixin from "../../../common/Mixin/Global/DatatableHelperMixin";
import {formatDateToLocal, isAfterNow, isSameOrAfterThisYear} from "../../../common/Helper/Support/DateTimeHelper";
import {EVENTS, SELECTABLE_DEPARTMENT} from "../../Config/ApiUrl";

export default {
    mixins: [HelperMixin, DatatableHelperMixin, DepartmentFilterMixin],
    data() {
        return {
            selectedUrl: '',
            isModalActive: false,
            SELECTABLE_DEPARTMENT,
            options: {
                name: this.$t('calender'),
                url: EVENTS,
                showHeader: true,
                responsive: true,
                cardViewIcon: 'calendar',
                cardViewComponent: 'app-calender-adm',
                cardViewEmptyBlock: false,
                cardViewPagination: false,
                cardViewQueryParams: {
                    'view_type': 'calendar'
                },
                columns: [
                    {
                        title: this.$t('name'),
                        type: 'text',
                        key: 'name',
                        isVisible: true,
                    },
                    {
                        title: this.$t('start_date'),
                        type: 'custom-html',
                        key: 'start_date',
                        isVisible: true,
                        modifier: (start_date) => formatDateToLocal(start_date),
                    },
                    {
                        title: this.$t('end_date'),
                        type: 'custom-html',
                        key: 'end_date',
                        isVisible: true,
                        modifier: (end_date) => formatDateToLocal(end_date),
                    },
                    {
                        title: this.$t('description'),
                        type: 'custom-html',
                        key: 'description',
                        isVisible: true,
                        modifier: (value) => {
                            return value ? value : '-';
                        }
                    },
                    {
                        title: this.$t('available_for'),
                        type: 'custom-html',
                        key: 'departments',
                        isVisible: true,
                        modifier: (departments) => {
                            let departmentName = '';
                            departments.forEach((element, index) => {
                                departmentName += index > 0 ? ',' + '<br>' + element.name : element.name;
                            });
                            departmentName = departmentName ? departmentName : this.$t('all');
                            return departmentName;
                        }
                    },
                    {
                        title: this.$t('created'),
                        type: 'custom-html',
                        key: 'created_at',
                        isVisible: true,
                        modifier: (value) => {
                            return formatDateToLocal(value);
                        }
                    },
                    this.$can('update_holidays') || this.$can('delete_holidays') ?
                        {
                            title: this.$t('actions'),
                            type: 'action',
                            isVisible: true
                        } : {},
                ],
                filters: [
                    {
                        title: this.$t('department'),
                        type: "multi-select-filter",
                        key: "departments",
                        option: [],
                        listValueField: 'name',
                        permission: !!this.$can('view_departments')
                    },
                    {
                        title: this.$t('time_period'),
                        type: "range-picker",
                        key: "time_period",
                        option: ["lastWeek", "thisWeek", "nextWeek",
                            "lastMonth", "thisMonth", "nextMonth",
                            "lastYear", "thisYear"]
                    },
                ],
                actions: [
                    {
                        title: this.$t('edit'),
                        icon: 'edit',
                        type: 'edit',
                        component: 'app-calender-modal',
                        modalId: 'calender-create-edit-modal',
                        modifier: (row) => this.$can('update_holidays') && isSameOrAfterThisYear(row.start_date)
                    },
                    {
                        title: this.$t('delete'),
                        icon: 'trash-2',
                        type: 'modal',
                        component: 'app-confirmation-modal',
                        modalId: 'app-confirmation-modal',
                        url: EVENTS,
                        name: 'delete',
                        modifier: (row) => this.$can('delete_holidays') && isAfterNow(row.start_date)
                    }
                ],
                showAction: true,
                actionType: "default",
                rowLimit: 10,
                orderBy: 'desc',
                paginationType: "pagination"
            },
        }
    },
    methods: {
        openModal() {
            this.selectedUrl = '';
            this.isModalActive = true;
        },
        triggerActions(row, action, active) {
            if (action.type === 'edit') {
                this.selectedUrl = `${EVENTS}/${row.id}`;
                this.isModalActive = true;
            } else {
                this.getAction(row, action, active)
            }
        }
    }
}
