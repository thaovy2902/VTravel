<template>
  <auth-form>
    <a-col :xs="24" :sm="24" :md="24" :lg="24" style="padding: 40px 160px;">
    <div>
      <h2 style="color:#2595ff; text-align: center">FORGOT PASSWORD</h2>
    </div>
    <a-form :form="form" @submit.prevent="handleSubmit">
      <div v-if="!isSuccess">
        <a-form-item has-feedback>
          <a-input
            size="large"
            v-decorator="[
              'email',
              {
                rules: [
                  { type: 'email', message: 'The email address you have entered is not valid.' },
                  { required: true, message: 'This filed is required.' },
                ]
              }
            ]"
            placeholder="Email"
          >
            <a-icon slot="prefix" type="mail" style="color: rgba(0,0,0,.25)" />
          </a-input>
        </a-form-item>
        <a-form-item>
          <a-button size="large" type="primary" html-type="submit" :loading="loading" block>
            Send
          </a-button>
        </a-form-item>
      </div>
      <div v-else>
        <a-form-item>
          <a-alert
            message="Success"
            description="A password reset link was sent. Please check your email!"
            type="success"
            showIcon
          />
        </a-form-item>
      </div>
      <div class="foot">
        <router-link :to="{ name: 'login' }">Log In</router-link>
        <router-link :to="{ name: 'register' }">Sign Up</router-link>
      </div>
    </a-form>
    </a-col>
    
  </auth-form>
</template>

<script>
  import { mapActions, mapGetters } from "vuex";
  import AuthForm from "@/components/auth/AuthForm";
  export default {
    components: { AuthForm },
    metaInfo: {
      title: "Forgot Password",
    },
    data() {
      return {
        isSuccess: false
      };
    },
    computed: {
      ...mapGetters("auth", ["loading"])
    },
    beforeCreate() {
      this.form = this.$form.createForm(this);
    },
    methods: {
      ...mapActions("auth", ["forgotPassword"]),
      handleSubmit(e) {
        e.preventDefault();
        this.form.validateFields((err, values) => {
          if (!err) {
            this.forgotPassword(values)
              .catch(() => {
              })
          }
        });
      },
    }
  };
</script>

<style scoped>
  .foot {
    display: flex;
    flex-direction: row;
    align-items: center;
    justify-content: space-between;
  }
</style>
