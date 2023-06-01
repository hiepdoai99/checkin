<template>
    <div class="card card-with-shadow h-100 min-height-350">
        <div class="card-header bg-transparent p-primary d-flex justify-content-between align-items-center">
            <h5 class="col-6 card-title text-capitalize mb-0">{{ $t('statistical') }}</h5>
            <ul class="col-6 nav tab-filter-menu justify-content-between align-items-center">
                <li>
                    <app-filter :filters="filters" @get-values="getFilterValues" />
                </li>
                <li class="nav-item chart-data-list" v-for="(item, index) in employeeStatisticFilters" :key="index">
                    <a href="#" class="nav-link data-group-item py-0"
                        :class="{ 'active': index === activeEmployeeStatisticsFilterIndex }"
                        @click.prevent="getFilterValue(item, index)">
                        <span class="square" :style="`background-color: ${item.color}`" />
                        {{ item.value }}
                    </a>
                </li>
            </ul>
        </div>
        <div class="card-body position-relative" :class="{ 'loading-opacity': preloader }">
            <app-overlay-loader v-if="preloader" />
            <app-chart v-else type="custom-line-chart" :height="380" :maximum-range="20" :labels="employeeStatisticsLabels"
                :data-sets="employeeStatisticsDataSet" />
        </div>
    </div>
</template>

<script>
import { numberFormatter } from "../../../../../common/Helper/Support/SettingsHelper";
import { axiosGet } from "../../../../../common/Helper/AxiosHelper";
import { APP_DASHBOARD } from "../../../../Config/ApiUrl";

export default {
    name: "employeeStatistics",
    data() {
        return {
            numberFormatter,
            preloader: false,

            activeEmployeeStatisticsFilterIndex: 0,
            employeeStatisticFilterValue: 'by_employment_status',
            employeeStatisticFilters: [],
            employeeStatisticsLabels: [],
            employeeStatisticsDataSet: [
                {
                    borderWidth: 1,
                    barThickness: 25,
                    barPercentage: 1,
                    data: [],
                    borderColor: '#89c111',
                    backgroundColor: '#e6efe2',
                },
                {
                    borderWidth: 1,
                    barThickness: 25,
                    barPercentage: 2,
                    data: [],
                    borderColor: '#019AFF',
                    backgroundColor: '#ddecfb'
                }
            ],
            filters: [
                {
                    "title": "Date Range",
                    "type": "range-picker",
                    "key": "date",
                    "option": ["today", "thisMonth", "last7Days", "nextYear"]
                },
            ],
        }
    },
    created() {
        this.setEmployeeStatisticFilters()
        this.getEmployeeStatisticsData();
    },
    methods: {
        setEmployeeStatisticFilters() {
            if (!!this.$can('view_employment_statuses')) {
                this.employeeStatisticFilters.push({ id: 'by_employment_status', value: this.$t('Số công'), color: '#89c111' })
            }
            if (!!this.$can('view_designations')) {
                this.employeeStatisticFilters.push({ id: 'by_designation', value: this.$t('Số lượt đi muộn'), color: '#019AFF' })
            }
            // if (!!this.$can('view_departments')) {
            //     this.employeeStatisticFilters.push({ id: 'by_department', value: this.$t('by_department') })
            // }

            this.employeeStatisticFilterValue = this.employeeStatisticFilters[0]?.id

        },
        getFilterValue(item, index) {
            this.employeeStatisticFilterValue = item.id;
            this.activeEmployeeStatisticsFilterIndex = index;
            this.getEmployeeStatisticsData();
        },
        getEmployeeStatisticsData() {
            // this.preloader = true;
            // axiosGet(`${APP_DASHBOARD}/employee-statistics?key=${this.employeeStatisticFilterValue}`)
            //     .then(({ data }) => {
            //         this.employeeStatisticsLabels = Object.keys(data);
            //         // this.employeeStatisticsDataSet[0].data = Object.values(data);
            //         // this.employeeStatisticsDataSet[0].borderColor = Object.entries(data).map(color => '#019AFF');
            //         // this.employeeStatisticsDataSet[0].backgroundColor = Object.entries(data).map(color => '#019AFF');
            //         this.preloader = false;
            //     })
            //     .catch((err) => {
            //         this.$toastr.e(err.message);
            //     })
            this.employeeStatisticsLabels = Object.values( ['T2', 'T3', 'T4', 'T5', 'T6', 'T7'] );
            this.employeeStatisticsDataSet[0].data = Object.values(['10', '12', '20', '15', '20', '7']);
            this.employeeStatisticsDataSet[1].data = Object.values(['12', '20', '25', '20', '25', '10']);
        },
        getFilterValues(values) {
            console.log(values);
        },

    },
}
</script>
