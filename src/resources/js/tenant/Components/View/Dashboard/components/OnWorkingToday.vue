<template>
    <div class="card card-with-shadow border-0 h-100 min-height-350">
        <div class="card-header bg-transparent p-4 d-flex justify-content-between align-items-center">
            <app-icon name="award" stroke="orange" />
            <h5 class="card-title text-capitalize mb-0">
                {{ $t('employee_list_excellent') }}
            </h5>
        </div>
        <div class="d-flex flex-column justify-content-center align-items-center mt-2 "
            :class="{ 'loading-opacity': preloader }">
            <app-overlay-loader v-if="preloader" />
            <template v-else>
                <!-- <app-chart class="mb-primary" type="dough-chart" :height="230" :labels="todayOverviewLabels"
                    :data-sets="todayOverviewDataSet" />
                <div class="chart-data-list">
                    <div class="row">
                        <div class="col-12" v-for="(item, index) in todayOverviewChartDataList" :key="index">
                            <div class="data-group-item px-0">
                                <span class="square" :style="`background-color: ${item.color}`" />
                                <span class="value">{{ item.name }} - {{ item.value }}</span>
                            </div>
                        </div>
                    </div>
                </div> -->
                <!-- <app-table class="w-100" id="department-table" :options="options" @action="triggerActions" /> -->
                <table class="table">
                    <thead>
                        <tr>
                            <th scope="col">STT</th>
                            <th scope="col">Tên</th>
                            <th scope="col">Phút đi muộn</th>
                            <th scope="col">Số công</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr v-for="(item, index) in details">
                            <th scope="row">{{ index +1 }}</th>
                            <td>{{ item?.full_name }}</td>
                            <td>{{ item?.in_time_late }}</td>
                            <td>{{ item?.work }}</td>
                        </tr>
                    </tbody>
                </table>
            </template>
        </div>
    </div>
</template>

<script>
import { numberFormatter } from "../../../../../common/Helper/Support/SettingsHelper";
import { axiosGet } from "../../../../../common/Helper/AxiosHelper";
import { REPORT_EMPLOYEE } from "../../../../Config/ApiUrl";
import OnWorkingMixin from '../../../Mixins/OnWorkingMixin';

export default {
    name: "OnWorkingToday",
    mixins: [OnWorkingMixin],
    data() {
        return {
            numberFormatter,
            preloader: false,
            details: {},
        }
    },
    created() {
        this.getDataEmployeeWorking();
    },
    methods: {
        getDataEmployeeWorking() {
            axiosGet(`${REPORT_EMPLOYEE}?page=1&within=lastMonth&month=Thg%2001&per_page=10`).then(({ data }) => {
                this.details = data?.data;
            })
        },
        triggerActions(row, action, active) {
            this.id = row.id;
            this.promptIcon = action.icon;
            this.modalClass = action.modalClass;
            this.promptSubtitle = action.promptSubtitle;

            if (action.name === 'edit') {
                this.selectedUrl = `${action.url}/${row.id}`;
                this.isDepartmentModalActive = true;
            } else if (['deactivate', 'activate'].includes(action.key)) {
                this.confirmationModalActive = true;
            } else if (action.name === 'move-employee') {
                this.id = row.id;
                this.full_name = row.full_name;
                this.isEmployeeMovementModalActive = true;
            } else {
                this.getAction(row, action, active)
            }
        }
    },
}
</script>
