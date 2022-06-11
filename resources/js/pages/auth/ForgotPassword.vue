<template>
  <auth-form>
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
            message="Thành công"
            description="Link reset mật khẩu đã được gửi về mail của bạn. Vui lòng check mail!"
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
  </auth-form>
</template>

<script>
  import { mapActions, mapGetters } from "vuex";
  import AuthForm from "@/components/auth/AuthForm";
  export default {
    components: { AuthForm },
    metaInfo: {
      title: "Quên mật khẩu",
    },
    data() {
      return {
        recaptcha: "",
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
            values.recaptcha = this.recaptcha;
            this.forgotPassword(values)
              .catch(() => {
                eventBus.$emit("resetReCaptcha");
              })
          }
        });
      },
      responseReCaptcha(data) {
        this.recaptcha = data;
      }
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
