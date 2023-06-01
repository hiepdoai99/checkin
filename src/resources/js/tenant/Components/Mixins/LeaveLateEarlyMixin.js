import {LEAVE_REQUESTS} from "../../Config/ApiUrl";
import {DepartmentFilterMixin, UserFilterMixin, WorkingShiftFilterMixin} from "./FilterMixin";
import {canUpdate, leaveDurations, leaveRequestStatusBuild} from "../View/Leave/Helper/Helper";

export default {
    mixins: [DepartmentFilterMixin, WorkingShiftFilterMixin, UserFilterMixin],
    data() {
        return {
            adminRequestId: '',
            isLeaveModalActive: false,
            isResponseLogModalActive: false,
            tableId: 'leave-request-table',
            options: {
                name: this.$allLabel('leave'),
                url: LEAVE_REQUESTS,
                showHeader: true,
                responsive: true,
                showSearch: true,
                showFilter: true,
                showCount: true,
                showClearFilter: true,
                enableRowSelect: this.$can('manage_approve_leave') && this.$can('manage_reject_leave'),
                columns: [
                    {
                        title: this.$t('first_last_name'),
                        type: 'component',
                        className: 'd-inline-flex',
                        componentName: 'app-attendance-employee-media-object',
                        key: 'user',
                    },
                    {
                        title: this.$t('date_and_time'),
                        type: 'component',
                        componentName: 'app-leave-date-time',
                        key: 'start_at',
                    },
                    {
                        title: this.$t('late_time'),
                        type: 'custom-html',
                        key: 'start_at',
                        modifier: (value, row) => leaveDurations(row)
                    },
                    // {
                    //     title: this.$t('leave_type'),
                    //     type: 'object',
                    //     key: 'type',
                    //     modifier: (value) => {
                    //         return value.name
                    //     }
                    // },
                    // {
                    //     title: this.$t('attachments'),
                    //     type: 'component',
                    //     componentName: 'app-attachments-column',
                    //     key: 'attachments',
                    // },
                    {
                        title: this.$t('status'),
                        type: 'custom-html',
                        key: 'status',
                        modifier: (value, row) => {
                            let status = leaveRequestStatusBuild(this.managerDept, row.last_review) || value
                            return `<span class="badge badge-pill badge-${status.class}">
                                ${status.translated_name}
                            </span>`
                        }
                    },
                    {
                        title: this.$t('activity'),
                        type: 'component',
                        componentName: 'app-activity-column',
                        key: 'comments',
                    },
                    {
                        title: this.$t('actions'),
                        type: 'action'
                    }
                ],
                filters: [
                    // {
                    //     title: this.$t('apply_date'),
                    //     type: 'date',
                    //     key: 'apply_date',
                    //     option: [],
                    //     listValueField: 'name'
                    // },
                    {
                        title: this.$t('department'),
                        type: 'multi-select-filter',
                        key: 'departments',
                        manager: true,
                        option: [],
                        listValueField: 'name',
                        permission: !!this.$can('view_departments')
                    },
                    // {
                    //     title: this.$t('work_shift'),
                    //     type: "multi-select-filter",
                    //     key: "working_shifts",
                    //     option: [],
                    //     listValueField: 'name',
                    //     permission: !!this.$can('view_working_shifts')
                    // },
                    // {
                    //     title : this.$t('see_rejected'),
                    //     type: 'toggle-filter',
                    //     key: 'rejected',
                    //     buttonLabel: {
                    //         active: 'Yes',
                    //         inactive: 'No'
                    //     },
                    //     header: {
                    //         title: this.$t('show_rejected_leaves'),
                    //         description: this.$t('filter_data_which_are_rejected')
                    //     }
                    // },
                    {
                        title: this.$t('late_time'),
                        type: 'drop-down-filter',
                        key: 'late_time',
                        option: [
                            {
                                id: 'five_minutes',
                                name: this.$t('five_minutes')
                            },
                            {
                                id: 'ten_minutes',
                                name: this.$t('ten_minutes')
                            },
                            {
                                id: 'thirty_minutes',
                                name: this.$t('thirty_minutes')
                            },
                            {
                                id: 'top_thirty_minutes',
                                name: this.$t('top_thirty_minutes')
                            },

                        ],
                        listValueField: 'name'
                    },
                    {
                        title: this.$t('users'),
                        type: "multi-select-filter",
                        key: "users",
                        option: [],
                        listValueField: 'full_name',
                        permission: !!this.$can('view_all_leaves')  && !!this.$can('view_users')
                    },
                ],
                actionType: 'dropdown',
                actions: [
                    {
                        title: this.$t('response_log'),
                        type: 'modal',
                        component: 'app-leave-response-log-modal',
                        modalId: 'response-log-modal',
                        name: 'response-log'
                    },
                    {
                        title: this.$t('approve'),
                        name: 'approved',
                        type: 'requestTableRow',
                        modalClass: 'success',
                        modalSubtitle: this.$t('you_are_going_to_approve_this_leave_request'),
                        modalIcon: 'check-circle',
                        modifier: (row) => this.$can('manage_approve_leave') &&
                            canUpdate(this.managerDept, row) && row.user_id != window.user.id
                    },
                    {
                        title: this.$t('reject'),
                        name: 'rejected',
                        type: 'requestTableRow',
                        modalClass: 'danger',
                        modalSubtitle: this.$t('you_are_going_to_reject_this_leave_request'),
                        modalIcon: 'check-circle',
                        modifier: (row) => this.$can('manage_reject_leave') &&
                            canUpdate(this.managerDept, row) && row.user_id != window.user.id
                    },
                    {
                        title: this.$t('cancel'),
                        name: 'canceled',
                        type: 'requestTableRow',
                        modalClass: 'warning',
                        modalSubtitle: this.$t('you_are_going_to_cancel_this_leave_request'),
                        modalIcon: 'check-circle',
                        modifier: (row) => (row.user_id == window.user.id || (row.assigned_by && row.assigned_by == window.user.id)) && row.status.name !== 'status_rejected'
                    },
                ],
                rowLimit: 10,
                paginationType: 'pagination'
            },
            isContextMenuOpen: false,
            allSelected: false,
            selectedRequests: [],
            filterValue: {},
        }
    },
    methods: {
        openLeaveModal(adminRequest = false) {
            this.adminRequestId = adminRequest ? window.user.id : '';
            this.isLeaveModalActive = true;
        },
        openResponseLogModal() {
            this.isResponseLogModalActive = true;
        },
        getSelectedRows(data, allSelected) {
            if (this.filterValue.rejected) {
                this.isContextMenuOpen = false;
                return;
            }
            this.allSelected = allSelected;
            this.selectedRequests = data;
            this.isContextMenuOpen = data.length;
        },
        afterBulkAction() {
            this.isContextMenuOpen = false;
            this.$hub.$emit('reload-leave-request-table')
        },
        getFilterValues(value) {
            this.filterValue = value;
        },
        triggerActions(row, action, active) {
            if (action.name === 'response-log') {
                this.isResponseLogModalActive = true;
            } else {
                this.getAction(row, action, active)
            }
        },
    }
}
