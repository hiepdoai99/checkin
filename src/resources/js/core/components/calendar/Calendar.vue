<template>
    <div class="calendar-view">
        <app-overlay-loader v-if="preloader" />
        <FullCalendar v-else :options="calendarOptions">
            <template v-slot:eventContent="arg">
                <b>{{ arg.timeText }}</b>
                <i>{{ arg.event.title }}</i>
            </template>
        </FullCalendar>
    </div>
</template>

<script>

import FullCalendar from "@fullcalendar/vue";
import dayGridPlugin from "@fullcalendar/daygrid";
import timeGridPlugin from "@fullcalendar/timegrid";
import interactionPlugin from "@fullcalendar/interaction";
import { INITIAL_EVENTS, createEventId } from './helper/calenderUnit'

export default {
    name: "Calendar",
    components: {
        FullCalendar
    },
    props: {
        options: {
            type: Object
        },
        preloader: {
            type: Boolean,
            default: false
        },

    },
    data() {
        return {
            initOptions: {
                plugins: [dayGridPlugin, timeGridPlugin, interactionPlugin],
                headerToolbar: {
                    left: 'title',
                    center: 'prev today next',
                    // right: 'dayGridMonth',
                    right: 'dayGridMonth,timeGridWeek,timeGridDay',
                },
                initialView: 'dayGridMonth',
                selectable: true,
                views: {
                    dayGridMonth: {
                        titleFormat: { month: 'long', year: 'numeric' }
                    },
                    timeGridWeek: {
                        titleFormat: { month: 'long', year: 'numeric' },
                        weekNumbers: true
                    },
                    timeGridDay: {
                        titleFormat: { day: 'numeric', month: 'long', year: 'numeric' },
                    }
                },
                selectMirror: true,
                dayMaxEvents: true,
                editable: true,
                weekends: true,
                select: this.handleDateSelect,
                eventClick: this.handleEventClick,
                eventsSet: this.handleEvents
            },
            currentEvents: [],
        }
    },
    computed: {
        calendarOptions() {
            return {
                ...this.initOptions,
                slotLabelFormat: this.slotLabel
                , ...this.options
            }
        },
        // slotLabel() {
        //     if (this.$store.state.settings.timeFormat === 12) return undefined;
        //     return {
        //         'hour12': false,
        //         'hour': '2-digit',
        //         'minute': '2-digit'
        //     }
        // }
    },
    methods: {
        handleDateSelect(selectInfo) {
            let title = prompt('Thêm ghi chú')
            let calendarApi = selectInfo.view.calendar
            calendarApi.unselect() // clear date selection

            if (title) {
                calendarApi.addEvent({
                    id: createEventId(),
                    title,
                    start: selectInfo.startStr,
                    end: selectInfo.endStr,
                    allDay: selectInfo.allDay
                })
            }
        },
        handleEventClick(clickInfo) {
            if (confirm(`Xóa ghi chú '${clickInfo.event.title}'`)) {
                clickInfo.event.remove()
            }
        },
        handleEvents(events) {
            this.currentEvents = events
        },
    }
}
</script>
