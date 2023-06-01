<template>
    <modal id="calender-create-edit-modal"
           size="large"
           v-model="showModal"
           :title="generateModalTitle('event')"
           @submit="submit"
           :loading="loading"
           :preloader="preloader">
           <form ref="form"
                :data-url='selectedUrl ? selectedUrl : apiUrl.EVENTS'
                @submit.prevent="submitData">
            <div v-if="updatePermission">
                <app-form-group
                    :label="$t('name')"
                    :placeholder="$placeholder('name','')"
                    v-model="formData.name"
                    :required="true"
                    :error-message="$errorMessage(errors, 'name')"
                />

                <div class="row">
                    <div class="col-md-6">
                        <!--:min-date="minDate"-->
                        <app-form-group
                            :label="$fieldTitle('start_date', '')"
                            :id="'start_date'"
                            type="date"
                            v-model="formData.start_date"
                            :placeholder="$placeholder('start_date', '')"
                            :required="true"
                            :error-message="$errorMessage(errors, 'start_date')"
                        />
                    </div>
                    <div class="col-md-6">
                        <app-form-group
                            :label="$fieldTitle('end_date', '')"
                            :id="'end_date'"
                            type="date"
                            :min-date="formData.start_date || minDate"
                            v-model="formData.end_date"
                            :placeholder="$placeholder('end_date', '')"
                            :required="true"
                            :error-message="$errorMessage(errors, 'end_date')"
                        />
                    </div>
                </div>

                <app-form-group-selectable
                    :fetch-url="apiUrl.SELECTABLE_HOLIDAY_DEPARTMENTS"
                    :label="$fieldTitle('available_for_as_default_to_all', '')"
                    :id="'departments'"
                    type="multi-select"
                    v-model="formData.departments"
                    :list="departments"
                    list-value-field="name"
                    :error-message="$errorMessage(errors, 'departments')"
                    :recommendation="$fieldTitle('you_can_set_the_holiday_only_for_specific_department_by_adding_them', '')"
                />

                <app-form-group
                    type="textarea"
                    :label="$fieldLabel('description', '')"
                    :placeholder="$textAreaPlaceHolder('description')"
                    v-model="formData.description"
                    :required="true"
                    :error-message="$errorMessage(errors, 'description')"
                />
            </div>

            <app-note v-else class="mb-4"
                      :title="$t('note')"
                      :notes="$t('update_note_this_year_expended_holiday')"
            />

        </form>
    </modal>
</template>

<script>
import FormHelperMixins from "../../../../../common/Mixin/Global/FormHelperMixins";
import ModalMixin from "../../../../../common/Mixin/Global/ModalMixin";
import {mapState} from "vuex";
import {formatDateForServer, isAfterNow} from "../../../../../common/Helper/Support/DateTimeHelper";

export default {
    name: "EventCreateEditModal",
    mixins: [FormHelperMixins, ModalMixin],
    props: {
        eventData: {}
    },
    data() {
        return {
            formData: {
                name: '',
                start_date: '',
                end_date: '',
                description: '',
                departments: [],
            },
        }
    },
    methods: {
        submit() {
            let formData = {...this.formData};

            formData.start_date = formatDateForServer(this.formData.start_date);
            formData.end_date = formatDateForServer(this.formData.end_date);

            this.fieldStatus.isSubmit = true;
            this.loading = true;
            this.message = '';
            this.errors = {};
            this.save(formData);
        },
        afterSuccess({data}) {
            this.formData = {};
            $('#calender-create-edit-modal').modal('hide');
            this.$emit('input', false);
            this.toastAndReload(data.message, 'event-table');
        },
        afterSuccessFromGetEditData({data}) {
            this.preloader = false;
            this.formData = data;
            this.formData.departments = this.collection(data.departments).pluck();
            this.formData.start_date = new Date(data.start_date);
            this.formData.end_date = new Date(data.end_date);
        },
    },
    mounted() {
        if (!this.selectedUrl && this.eventData) {
            let endDate = this.eventData.end
            this.formData.end_date = new Date(endDate.setSeconds(endDate.getSeconds() - 1));
            this.formData.start_date = this.eventData.start;
        }
    },
    computed: {
        ...mapState({
            departments: state => state.department.data
        }),

        updatePermission(){
            return this.selectedUrl ? isAfterNow(this.formData.start_date) : true;
        },
        minDate(){
            return new Date((new Date()).setDate((new Date).getDate() + 1))
        }
    },
}
</script>
