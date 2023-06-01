<template>
    <div class="content-wrapper bg-white">
        <app-page-top-section :title="$t('calendar')">
            <app-default-button
                :title="$fieldTitle('add', 'calendar', true)"
                v-if="$can('create_holidays')"
                @click="openModal"
            />
        </app-page-top-section>

        <app-table
            id="event-table"
            :options="options"
            :card-view="true"
            @action="triggerActions"
        />

        <app-calender-modal
            v-if="isModalActive"
            v-model="isModalActive"
            :selected-url="selectedUrl"
            @close="isModalActive = false"
        />

        <app-confirmation-modal
            v-if="confirmationModalActive"
            :firstButtonName="$t('yes')"
            modal-class="warning"
            icon="trash-2"
            modal-id="app-confirmation-modal"
            @confirmed="confirmed('event-table')"
            @cancelled="cancelled"
        />
    </div>
</template>

<script>
import CalenderEventsMixins from "../../../Mixins/CalenderEventsMixins";
import { EVENTS } from "../../../../Config/ApiUrl";

export default {
    name: "Event",
    mixins: [CalenderEventsMixins],
    data() {
        return {
            selectedUrl: '',
            isModalActive: false,
        }
    },
    methods: {
        openModal() {
            this.selectedUrl = '';
            this.isModalActive = true;
        },
        triggerActions(row, action, active) {
            if (action.type === 'edit') {
                this.selectedUrl = `${EVENTS}/${row.id}`;
                this.isModalActive = true;
            } else {
                this.getAction(row, action, active)
            }
        }
    }
}
</script>

