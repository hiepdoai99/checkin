<template>
    <div class="content-wrapper bg-white">
        <app-page-top-section :title="$t('leave_status')">
            <leave-top-buttons @open-model="openLeaveModal"/>
        </app-page-top-section>

        <app-filter-with-search
            :options="options"
            @filteredValue="setFilterValues"
            @searchValue="setSearchValue"
        />

        <div class="card card-with-shadow border-0">
            <div class="card-header d-flex align-items-center justify-content-between p-primary primary-card-color bg-gray-1 rounded-top-2">
                <app-month-calendar :periods-url="apiUrl.SELECTABLE_LEAVE_PERIODS" />

                <app-period-calendar />
            </div>
            <div class="card-body bg-sky-3">
                <div class="row mb-primary" v-if="!preloader && this.$can('view_all_leaves')">
                    <div class="col-md-6 col-lg-3">
                        <div class="bg-white text-center rounded p-3 shadow-1 ">
                            <h5>{{ summaries.on_leave }}</h5>
                            <p class="text-muted mb-0">{{ $t('employees_on_leave') }}</p>
                        </div>
                    </div>
                    <div class="col-md-6 col-lg-3">
                        <div class="bg-red-1 text-center rounded p-3 shadow-1">
                            <h5>{{ summaries.total_hours }}</h5>
                            <p class="text-muted mb-0">{{ $t('total_leave_hours') }}</p>
                        </div>
                    </div>
                    <div class="col-md-6 col-lg-3">
                        <div class="bg-gray-1 text-center rounded p-3 shadow-1">
                            <h5>{{ summaries.single_leave }}</h5>
                            <p class="text-muted mb-0">
                                {{ $t('on_leave') }}<span class="default-font-color">( {{ $t('single_day') }} )</span>
                            </p>
                        </div>
                    </div>
                    <div class="col-md-6 col-lg-3">
                        <div class="bg-warning text-center rounded p-3 shadow-1">
                            <h5>{{ summaries.multi_leave }}</h5>
                            <p class=" mb-0">
                                {{ $t('on_leave') }} <span class="default-font-color">({{ $t('multi_day') }})</span>
                            </p>
                        </div>
                    </div>
                </div>

                <app-table
                    class="remove-datatable-x-padding"
                    :id="tableId"
                    :options="tableOptions"
                    @action="triggerActions"
                />
            </div>
        </div>

        <app-leave-create-edit-modal
            v-if="isLeaveModalActive"
            v-model="isLeaveModalActive"
            @reload="reload"
            :specificId="adminRequestId"
        />

        <app-leave-response-log-modal
            v-if="isResponseLogModalActive"
            v-model="isResponseLogModalActive"
            :url="logUrl"
            :table-id="tableId"
        />

        <app-confirmation-modal
            v-if="confirmationModalActive"
            :message="modalSubtitle"
            :modal-class="modalClass"
            :icon="modalIcon"
            modal-id="app-confirmation-modal"
            @confirmed="updateStatus"
            @cancelled="cancelled"
        />
    </div>
</template>

<script>
import LeaveStatusMixin from "../../Mixins/LeaveStatusMixin";
import {axiosGet} from "../../../../common/Helper/AxiosHelper";
import {LEAVES} from "../../../Config/ApiUrl";
import LeaveRequestActionMixin from "../../Mixins/LeaveRequestActionMixin";
import LeaveTopButtons from "./Components/LeaveTopButtons";

export default {
    name: "LeaveStatus",
    mixins: [LeaveStatusMixin, LeaveRequestActionMixin],
    components: {LeaveTopButtons},
    data() {
        return {
            logUrl:'',
            summaries: {},
            preloader: false,
        }
    },
    methods: {
        getSummaries(){
            this.preloader = true;
            axiosGet(`${this.apiUrl.LEAVES}/summaries?${this.queryString}`).then(({data}) => {
                this.preloader = false;
                this.summaries = data;
            })
        },
        reload(){
            this.getSummaries();
            this.tableOptions.url = `${LEAVES}/data-table?${this.queryString}`
            this.$hub.$emit('reload-leave-status-table')
        }
    },
    computed: {
        queryString(){
            return this.$store.getters.getFilterUrls;
        },
    },
    watch: {
        queryString: {
            handler: function (queryString) {
                if (!queryString) {
                    return;
                }
                this.reload()
            },
            immediate: true
        }
    },
}
</script>