<template>
    <!--input-->
    <div class="form-group-with-icon" >
        <input :type="data.type"
               :name="name"
               :id="inputFieldId"
               :required="data.required"
            :class="['form-control ' + data.inputClass + 'text-right-padding']"
               :placeholder="data.placeholder"
               :readonly="data.readOnly"
               :disabled="data.disabled"
               v-bind:value="value"
               v-on="listeners"
               :autocomplete="data.autocomplete" />
        <div class="form-control-feedback cursor-pointer">
            <span
                class="">
                <app-icon name="mail" stroke="blue" class="size-19" />
            </span>
        </div>
    </div>
</template>

<script>
import {InputMixin} from './mixin/InputMixin.js';

export default {
    name: "EmailInput",
    mixins: [InputMixin]
}
</script>
