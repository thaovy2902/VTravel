<template>
  <auth-form>
    <a-form :form="form" @submit.prevent="handleSubmit">
      <a-form-item has-feedback>
        <a-input
          size="large"
          v-decorator="[
            'name',
            {
              rules: [
                { required: true, message: 'Vui lòng nhập họ tên' },
                { max: 255, message: 'Tối đa 255 ký tự' }
              ]
            }
          ]"
          placeholder="Họ tên"
        >
          <a-icon slot="prefix" type="user" style="color: rgba(0,0,0,.25)" />
        </a-input>
      </a-form-item>
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
                { max: 255, message: 'Tối đa 255 ký tự' },
                { validator: validateToNextPassword }
              ]
            }
          ]"
          type="password"
          placeholder="Mật khẩu"
        >
          <a-icon slot="prefix" type="lock" style="color: rgba(0,0,0,.25)" />
        </a-input>
      </a-form-item>
      <a-form-item has-feedback>
        <a-input
          size="large"
          v-decorator="[
            'confirmPassword',
            {
              rules: [
                { required: true, message: 'Không được trống' },
                {
                  validator: compareToFirstPassword
                }
              ]
            }
          ]"
          type="password"
          placeholder="Xác nhận mật khẩu"
          @blur="handleConfirmBlur"
        >
          <a-icon slot="prefix" type="lock" style="color: rgba(0,0,0,.25)" />
        </a-input>
      </a-form-item>
      <a-form-item>
        <ReCaptcha ref="recaptcha" @responseReCaptcha="responseReCaptcha" />
      </a-form-item>
      <a-form-item>
        <a-button size="large" type="primary" html-type="submit" :loading="loading" block>
          Đăng ký
        </a-button>
      </a-form-item>
      <div class="foot">
        <span>
          Hoặc
          <router-link :to="{ name: 'login' }">đăng nhập</router-link>
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
      title: "Đăng ký",
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
      this.form = this.$form.createForm(this, { name: "form_register" });
    },
    methods: {
      ...mapActions("auth", ["register"]),
      handleSubmit(e) {
        e.preventDefault();
        this.form.validateFields((err, values) => {
          if (!err) {
            values.recaptcha = this.recaptcha;
            this.register(values)
              .then(() => {
                this.$router.push({ name: "home" });
              })
              .catch(() => {
                eventBus.$emit("resetReCaptcha");
              });
          }
        });
      },
      responseReCaptcha(data) {
        this.recaptcha = data;
      },
      handleConfirmBlur(e) {
        const value = e.target.value;
        this.confirmPassword = this.confirmPassword || !!value;
      },
      compareToFirstPassword(rule, value, callback) {
        const form = this.form;
        if (value && value !== form.getFieldValue("password")) {
          callback("Mật khẩu nhập lại không đúng");
        } else {
          callback();
        }
      },
      validateToNextPassword(rule, value, callback) {
        const form = this.form;
        if (value && this.confirmPassword) {
          form.validateFields(["confirmPassword"], { force: true });
        }
        callback();
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
