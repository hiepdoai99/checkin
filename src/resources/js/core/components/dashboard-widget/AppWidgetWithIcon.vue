<template>
    <div class="card card-with-shadow dashboard-widgets dashboard-icon-widget p-primary border-0"
        :class="data.className">
        <div class="icon-wrapper" :class="data.classItem">
            <app-icon :name="data.icon" />
        </div>
        <div class="widget-data">
            <h6>{{ data.number }}</h6>
            <p>{{ data.label }}</p>
        </div>
    </div>
</template>

<script>
import { WidgetMixin } from "./mixins/WidgetMixin.js";

export default {
    name: "AppWidgetWithIcon",
    mixins: [WidgetMixin],
}
</script>
