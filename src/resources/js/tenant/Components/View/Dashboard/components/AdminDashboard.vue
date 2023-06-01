<template>
    <div>
        <div class="position-relative" v-if="adminSummaryPermissions">
            <app-overlay-loader v-if="preloader" />
            <div class="row mb-primary" :class="{ 'loading-opacity': preloader }">
                <div class="col-sm-6 col-lg-3 mb-4 mb-lg-0" v-if="$can('view_employees')">
                    <app-widget type="app-widget-with-icon" :label="$t('view_employees')" className="bg-sky-2"
                        classItem="bg-sky-1" :number="numberFormatter(cardSummaries.total_employee)" icon="users" />
                </div>
                <div class="col-sm-6 col-lg-3 mb-4 mb-lg-0" v-if="$can('view_departments')">
                    <app-widget type="app-widget-with-icon" :label="$t('checkin')" className="bg-red-1"
                        classItem="bg-orange-1" :number="numberFormatter(cardSummaries.total_department)"
                        icon="check-circle" />
                </div>
                <div class="col-sm-6 col-lg-3 mb-4 mb-sm-0" v-if="$can('view_all_leaves')">
                    <app-widget type="app-widget-with-icon" :label="$t('late')" className="bg-gray-1"
                        classItem="bg-green-1" :number="numberFormatter(cardSummaries.total_leave_request)"
                        icon="clock" />
                </div>
                <div class="col-sm-6 col-lg-3" v-if="$can('view_all_leaves')">
                    <app-widget type="app-widget-with-icon" :label="$t('not_checkin')" className="bg-sky-3"
                        classItem="bg-sky-4" :number="numberFormatter(cardSummaries.on_leave_today)" icon="x-square" />
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-12 col-sm-12 col-md-8 col-lg-8 col-xl-9 mb-4 mb-md-0" v-if="employeeStatisticsPermissions">
                <app-employee-statistics />
            </div>
            <div class="col-12 col-sm-12 col-md-4 col-lg-4 col-xl-3" v-if="attendancePermissions">
                <on-working-today />
            </div>
            <div class="col-12 col-sm-12 col-md-6 col-lg-6 col-xl-6 mt-4">
                <!-- <app-calender /> -->
                <app-calender-event />
            </div>
            <div class="col-12 col-sm-12 col-md-6 col-lg-6 col-xl-6 mt-4">
                <!-- <app-calender /> -->
                <app-list-staff />
            </div>
        </div>
    </div>
</template>

<script>
import { numberFormatter } from "../../../../../common/Helper/Support/SettingsHelper";
import { axiosGet } from "../../../../../common/Helper/AxiosHelper";
import { APP_DASHBOARD, CHECK_DEFAULT_WORKING_SHIFT } from "../../../../Config/ApiUrl";
import OnWorkingToday from "./OnWorkingToday";

export default {
    name: "AdminDashboard",
    components: { OnWorkingToday },
    data() {
        return {
            numberFormatter,
            preloader: false,
            cardSummaries: {},
        }
    },
    created() {
        this.getSummeryData();
    },
    methods: {
        getSummeryData() {
            this.preloader = true;
            axiosGet(`${APP_DASHBOARD}/summery`).then(({ data }) => {
                this.cardSummaries = data;
                this.preloader = false;
            })
        },
    },
    computed: {
        adminSummaryPermissions() {
            return this.$can('view_employees') ||
                this.$can('view_departments') || this.$can('view_all_leaves')
        },
        employeeStatisticsPermissions() {
            return this.$can('view_employment_statuses') ||
                this.$can('view_designations') || this.$can('view_departments')
        },
        attendancePermissions() {
            return this.$can('view_all_attendance')
        }
    }
}
</script>

<style scoped>

</style>