<template>
    <div class="login-form d-flex align-items-center">
        <form class="sign-in-sign-up-form w-100" ref="form" data-url="admin/users/login" >
            <div class="">
                <div class="text-center mb-4">
                    <img :src="logoUrl" alt="" class="img-fluid logo">
                </div>
                <!-- <div class="form-row">
                    <div class="form-group col-12">
                        <h6 class="text-center mb-0">{{ $t('hi', {object: $t('there')}) }}!</h6>
                        <label class="text-center d-block">{{ $t('login_to_your_dashboard') }}</label>
                    </div>
                </div> -->
    
                <app-form-group
                    type="select"
                    v-if="demoCredentials"
                    v-model="loginRole"
                    :label="$t('login_as')"
                    list-value-field="name"
                    :list="selectableRole"
                />
                <app-form-group
                    :label="$t('email')"
                    type="email"
                    v-model="formData.email"
                    :placeholder="$placeholder('your', 'email')"
                    :error-message="$errorMessage(errors, 'email')"
                />
    
                <app-form-group
                        :label="$t('password')"
                        type="password"
                        v-model="formData.password"
                        :placeholder="$placeholder('your', 'password')"
                        :error-message="$errorMessage(errors, 'password')"
                        :show-password="true"
                />
                <div class="form-row">
                    <div class="col-6">
                        <app-input class="mb-primary" type="single-checkbox" :list-value-field="$t('remember_me')"
                            v-model="formData.remember_me" />
                    </div>
                </div>
                <div class="form-row justify-content-center">
                    <div class="form-group col-4">
                        <app-submit-button
                            btn-class="d-inline-flex btn-block text-center button-submit"
                            :label="$t('login')"
                            :loading="loading"
                            iconButton="log-in" 
                            @click="submitData"
                        />
                    </div>
                </div>
    
                <div class="form-row justify-content-center">
                    <div class="col-6 text-center">
                        <a :href="urlGenerator('/users/password-reset')" class="bluish-text">
                            <i data-feather="lock" class="pr-2"/>{{ $t('forget', {subject: $t('password')}) }}?
                        </a>
                    </div>
                </div>
            </div>

            <div class="form-group">
                <div class="row mt-5 mb-3">
                    <div class="col-6 col-sm-6 col-md-6 col-xl-6 text-center">
                        <a href="#" target="_blank">
                            <!-- <img src="../../../../../../public/images/Downloal-app-store.png"> -->
                            <img src="https://assets.stickpng.com/images/5a902db97f96951c82922874.png" alt="" class="img-fluid logo">
                        </a>
                    </div>
                    <div class="col-6 col-sm-6 col-md-6 col-xl-6 text-center">
                        <a href="#" target="_blank">
                            <!-- <img src="../../../../../../public/images/Downloal-google-play.png"> -->
                            <img src="https://seeklogo.com/images/G/get-it-on-google-play-badge-logo-8CDE582776-seeklogo.com.png" alt="" class="img-fluid logo" style="height: 57px;">
                        </a>
                    </div>
                </div>
                <div class="col-12">
                    <p class="text-center footer-copy">
                        {{ $t('copyright') }} @ {{ new Date().getFullYear() }} {{ $t('by') }} {{ appName }}
                    </p>
                </div>
            </div>
        </form>
    </div>
</template>

<script>
    import ThemeMixin from "../../Mixin/Global/ThemeMixin";
    import FormHelperMixins from "../../Mixin/Global/FormHelperMixins";

    export default {
        name: "Login",
        mixins: [ThemeMixin, FormHelperMixins],
        props: {
            logoUrl: {
                required: false
            },
            appName: {
                required: false
            },
            previousPage: {
                required: false
            },
            demo: {}
        },
        data() {
            return {
                loginRole: ''
            }
        },
        methods: {
            submitData() {
                this.message = '';
                this.loading = true;
                this.save(this.formData);
            },

            afterSuccess({ data }) {
                window.location.href = data;
            },
            afterFinalResponse(){},
        },
        computed: {
            demoCredentials(){
                return this.demo ? JSON.parse(this.demo) : '';
            },
            selectableRole(){
                return [{ id: '', name: this.$t('select_role') }]
                    .concat(Object.keys(this.demoCredentials).map((item) => {
                        return {
                            id: item,
                            name: this.demoCredentials[item].role,
                        }
                    }))
            },
            loginRoleUpdate(){
                return this.loginRole
            }
        },
        watch: {
            loginRoleUpdate: {
                handler: function (role){
                    this.formData.password = role ? this.demoCredentials[role].password : '';
                    this.formData.email = role ? this.demoCredentials[role].email: '';
                }
            }
        }

    }
</script>
