import DatatableHelperMixin from "../../../common/Mixin/Global/DatatableHelperMixin";
import {WORKING_SHIFTS} from "../../Config/ApiUrl";
import {formatDateToLocal, formatUtcToLocal} from "../../../common/Helper/Support/DateTimeHelper";
import moment from "moment";

export default {
    mixins: [DatatableHelperMixin],
    data() {
        return {
            options: {
                name: this.$t('companyBranch'),
                url: WORKING_SHIFTS,
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
                        title: this.$t('founded_date'),
                        type: 'custom-html',
                        key: 'start_date',
                        isVisible: true,
                        modifier: start_date => start_date ? formatDateToLocal(start_date) : '-'
                    },
                    {
                        title: this.$t('branch_manager'),
                        type: 'custom-html',
                        key: 'name',
                        isVisible: true,
                        modifier: name => name ? name : '-'
                    },
                    {
                        title: this.$t('address'),
                        type: 'text',
                        key: 'name',
                        isVisible: true,
                        modifier: name => name ? name : '-'
                    },
                    {
                        title: this.$t('actions'),
                        type: 'action',
                        isVisible: true
                    },
                ],
                filters: [
                    {
                        title: this.$t('created'),
                        type: "range-picker",
                        key: "date",
                        option: ["today", "thisMonth", "last7Days", "thisYear"]
                    },
                    // {
                    //     title: this.$t('type'),
                    //     type: "checkbox",
                    //     key: "type",
                    //     option: [
                    //         {id: 'regular', value: this.$t('regular')},
                    //         {id: 'scheduled', value: this.$t('scheduled')},
                    //     ]
                    // }
                ],
                paginationType: "pagination",
                responsive: true,
                rowLimit: 10,
                showAction: true,
                orderBy: 'desc',
                actionType: "dropdown",
                actions: [
                    {
                        title: this.$t('edit'),
                        icon: 'edit',
                        type: 'modal',
                        name: 'edit',
                        modifier: row => this.$can('update_working_shifts') && !(!!parseInt(row.attendances_count))
                    },
                    {
                        title: this.$t('view_workshift'),
                        icon: 'eye',
                        type: 'modal',
                        name: 'edit',
                        modifier: row => row.attendances_count > 0 || !this.$can('update_working_shifts')
                    },
                    {
                        title: this.$t('delete'),
                        icon: 'trash',
                        message: this.$t('you_are_going_to_delete_message', { resource: this.$t('work_shift') }),
                        name: 'delete',
                        modifier: row => this.$can('delete_working_shifts') &&
                            !(!!parseInt(row.is_default)) && !(!!parseInt(row.attendances_count))
                    },
                    {
                        title: this.$addLabel('branch'),
                        icon: 'user',
                        type: 'modal',
                        name: 'add-employee',
                        modifier: row => this.$can('add_employees_to_working_shift') &&
                            !(!!parseInt(row.is_default)) && !moment(row.end_date).isBefore(new Date())
                    }
                ],
            }
        }
    }
}
