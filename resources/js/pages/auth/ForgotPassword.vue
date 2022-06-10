<template>
  <auth-form>
    <a-form :form="form" @submit.prevent="handleSubmit">
      <div v-if="!isSuccess">
        <a-form-item has-feedback>
          <a-input
            size="large"
            v-decorator="[
              'email',
              {
                rules: [
                  { type: 'email', message: 'Email không hợp lệ' },
                  { required: true, message: 'Vui lòng nhập email' },
                  { max: 255, message: 'Tối đa 255 ký tự' }
                ]
              }
            ]"
            placeholder="Email"
          >
            <a-icon slot="prefix" type="mail" style="color: rgba(0,0,0,.25)" />
          </a-input>
        </a-form-item>
        <a-form-item>
          <ReCaptcha ref="recaptcha" @responseReCaptcha="responseReCaptcha" />
        </a-form-item>
        <a-form-item>
          <a-button size="large" type="primary" html-type="submit" :loading="loading" block>
            Gửi
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
        <router-link :to="{ name: 'login' }"> Đăng nhập</router-link>
        <router-link :to="{ name: 'register' }">Đăng ký</router-link>
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
