<template>
    <div class="content-wrapper bg-white">
        <app-page-top-section :title="$t('calender')"/>

        <div class="row">
            <div class="col-2">
                đang phát triển
            </div>
            <div class="col-8">
                <lunar-calendar 
                    :firstDayOfWeek="parseInt(firstDayOfWeek)"
                    :disableDaysBeforeToday="disableDaysBeforeToday"
                    :defaultDate="defaultDate"
                    :showLunarButton="false"
                    :showLunar="true"
                    :lang="lang"
                    :dateLang="dateLang"
                    :cellClass="$style.days"
                ></lunar-calendar>

            </div>
            <div class="col-2"></div>
        </div>
    
</div>
</template>

<script>

export default {
    name: "Calendar",
    props: {
        msg: String
    },
    data() {
        return {
                solarDate: "",
                lunarDate: "",
                showCalendar: true,
                disableDaysBeforeToday: false,
                defaultDate: null,
                firstDayOfWeek: 0,
                showLunar: true,
                inputDate: "",
                lang: "vi",
                dateLang: "vi",
        };
    },
}
</script>
<style module>
.days .lunar {
    font-size: small;
}
</style>