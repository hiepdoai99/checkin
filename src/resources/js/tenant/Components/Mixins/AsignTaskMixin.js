import {ASSIGN_TASK} from "../../Config/ApiUrl";
import {DepartmentFilterMixin, WorkingShiftFilterMixin} from "./FilterMixin";
import {canUpdate, leaveDurations, leaveRequestStatusBuild} from "../View/Leave/Helper/Helper";
import moment from "moment";

export default {
    mixins: [DepartmentFilterMixin, WorkingShiftFilterMixin,],
    data() {
        return {
            adminRequestId: '',
            isLeaveModalActive: false,
            isResponseLogModalActive: false,
            tableId: 'assign-task-table',
            options: {
                name: this.$allLabel('leave'),
                url: ASSIGN_TASK,
                showHeader: true,
                responsive: true,
                showSearch: true,
                showFilter: true,
                showClearFilter: true,
                columns: [
                    {
                        title: this.$t('first_last_name'),
                        type: 'custom-html',
                        className: 'd-inline-flex',
                        key: 'user',
                        modifier: item => item.full_name,
                    },
                    {
                        title: this.$t('title'),
                        type: 'custom-html',
                        key: 'name',
                        modifier: (name) => name,
                    },
                    {
                        title: this.$t('start_date'),
                        type: 'custom-html',
                        key: 'start_date',
                        modifier: start_date => moment(start_date).format('DD/MM/YYYY'),
                    },
                    {
                        title: this.$t('end_date'),
                        type: 'custom-html',
                        key: 'end_date',
                        modifier: end_date => moment(end_date).format('DD/MM/YYYY'),
                    },
                    {
                        title: this.$t('status'),
                        key: 'status_id',
                        type: 'object',
                        modifier: (value) => {
                            return value
                        },
                    },
                    {
                        title: this.$t('job_asignor'),
                        type: 'custom-html',
                        key: 'assign_by',
                        modifier: item => item.full_name,
                    },
                    {
                        title: this.$t('desc_work'),
                        type: 'custom-html',
                        key: 'description',
                        modifier: item => item,
                    },
                    {
                        title: this.$t('actions'),
                        type: 'action',
                        isVisible: true
                    }
                ],
                filters: [
                    {
                        title: this.$t('date'),
                        type: 'range-picker',
                        key: 'date_range',
                        option: ["today", "thisMonth", "last7Days", "thisYear"]
                    },
                ],
                actionType: 'dropdown',
                actions: [
                    {
                        title: this.$t('edit'),
                        type: 'modal',
                        component: 'app-asign-task-edit-create-modal',
                        modalId: 'assign-task-create-edit-modal',
                        url: ASSIGN_TASK,
                        name: 'edit',
                    },
                ],
                rowLimit: 10,
                paginationType: 'pagination'
            },
            isContextMenuOpen: false,
            allSelected: false,
            filterValue: {},
            paginationOptions: [5, 10, 15],
            rowLimit: 10,
            paginationType: "pagination",

        }
    },
    methods: {
        openResponseLogModal() {
            this.isResponseLogModalActive = true;
        },
        afterBulkAction() {
            this.isContextMenuOpen = false;
            this.$hub.$emit('reload-assign-task-table')
        },
        getFilterValues(value) {
            this.filterValue = value;
        },

        // openModal() {
        //     this.selectedUrl = '';
        //     this.isModalActive = true;
        // },
        // triggerActions(row, action, active) {
        //     if (action.type === 'edit') {
        //         this.selectedUrl = `${ASSIGN_TASK}/${row.id}`;
        //         this.isModalActive = true;
        //     } else {
        //         this.getAction(row, action, active)
        //     }
        // }
    }
}
