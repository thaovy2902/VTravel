<template>
  <auth-form>
    <a-form :form="form" @submit.prevent="handleSubmit">
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
      <a-form-item has-feedback>
        <a-input
          size="large"
          v-decorator="[
            'password',
            {
              rules: [
                { required: true, message: 'Vui lòng nhập mật khẩu' },
                { min: 6, message: 'Tối thiểu 6 ký tự' },
                { max: 255, message: 'Tối đa 255 ký tự' }
              ]
            }
          ]"
          type="password"
          placeholder="Mật khẩu"
        >
          <a-icon slot="prefix" type="lock" style="color: rgba(0,0,0,.25)" />
        </a-input>
      </a-form-item>
      <a-form-item>
        <a-button size="large" type="primary" html-type="submit" :loading="loading" block>
          Đăng nhập
        </a-button>
      </a-form-item>
      <div class="foot">
        <span>
          Hoặc
          <router-link :to="{ name: 'register' }">đăng ký</router-link>
        </span>
        <router-link :to="{ name: 'forgotpassword' }" style="color:#ff0000">Quên mật khẩu?</router-link>
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
      title: "Đăng nhập",
    },
    data() {
      return {
        recaptcha: ""
      };
    },
    computed: {
      ...mapGetters("auth", ["loading"])
    },
    beforeCreate() {
      this.form = this.$form.createForm(this);
    },
    methods: {
      ...mapActions("auth", ["login"]),
      handleSubmit(e) {
        e.preventDefault();
        this.form.validateFields((err, values) => {
          if (!err) {
            values.recaptcha = this.recaptcha;
            this.login(values).catch(() => {
              eventBus.$emit("resetReCaptcha");
            });
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
