<template>
    <div class="card card-with-shadow border-0 h-100 min-height-350 tab-custom">
        <div class="card-header bg-transparent p-primary d-flex justify-content-start align-items-center">
            <!-- <h5 class="card-title text-capitalize mb-0">{{ $t('total_attendance_today') }} - {{
                    numberFormatter(attendance.total)
                }}</h5> -->
            <app-icon name="file-text" stroke="blue" />
            <h5 class="card-title text-capitalize mb-0">
                {{ $t('report_employee') }}
            </h5>
        </div>
        <app-tab type="horizontal" :tabs="tabsDepartment" />
    </div>
</template>

<script>
export default {
    name: "Department",
    data() {
        return {
            tabsDepartment: [
                {
                    name: this.$t('late'),
                    title: this.$t('late'),
                    component: "app-list-late",
                    permission: true,
                },
                {
                    name: this.$t('back_early'),
                    title: this.$t('back_early'),
                    component: "app-list-early",
                    permission: true,
                },
                {
                    name: this.$t('business_travel'),
                    title: this.$t('business_travel'),
                    component: "app-list-bussiness-travel",
                    permission: true,
                },
                {
                    name: this.$t('leaver'),
                    title: this.$t('leaver'),
                    component: "app-list-leave",
                    permission: true,
                },
            ]
        }
    }
}
</script>
