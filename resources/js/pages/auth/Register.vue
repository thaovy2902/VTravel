<template>
  <auth-form>
        <a-col :xs="4" :sm="4" :md="9" :lg="9">
      <div class="left-column">
        <div class="left-column-content">
          <h2>WELCOME BACK!</h2>
          <p style="margin-bottom: 5px;">If you already have an account</p>
          <p style="margin-bottom: 40px;">Please Log In form here!</p>
          <span class="link-span">
            <router-link :to="{ name: 'login' }">Log In</router-link>
        </span>
        </div>
      </div>
    </a-col>
    <a-col :xs="4" :sm="4" :md="15" :lg="15" style="padding: 0 80px 40px;">
      <div class="logo">
        <router-link :to="{ name: 'home' }">
          <img src="/img/logo.svg" alt="logo" />
        </router-link>
      </div>  
      <div>
      <h2 style="color:#2595ff; text-align: center; margin-top: 20px;">CREATE ACCOUNT</h2>
    </div>
    <a-form :form="form" @submit.prevent="handleSubmit">
      <a-form-item has-feedback>
        <a-input
          size="large"
          v-decorator="[
            'name',
            {
              rules: [
                { required: true, message: 'This filed is required.' },
                { max: 255, message: 'Name may not be greater than 255 characters.' }
              ]
            }
          ]"
          placeholder="Full Name"
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
                { type: 'email', message: 'The email address you have entered is not valid.' },
                { required: true, message: 'This filed is required.' },
                { max: 255, message: 'Email may not be greater than 255 characters.' }
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
                { required: true, message: 'This filed is required.' },
                { min: 6, message: 'Passwords must be at least 6 characters long.' },
                { validator: validateToNextPassword }
              ]
            }
          ]"
          type="password"
          placeholder="Password"
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
                { required: true, message: 'This filed is required.' },
                {
                  validator: compareToFirstPassword
                }
              ]
            }
          ]"
          type="password"
          placeholder="Comfirm Password"
          @blur="handleConfirmBlur"
        >
          <a-icon slot="prefix" type="lock" style="color: rgba(0,0,0,.25)" />
        </a-input>
      </a-form-item>
      <a-form-item>
        <a-button size="large" type="primary" html-type="submit" :loading="loading" block>
          Sign Up 
        </a-button>
      </a-form-item>
      <div class="foot">
        <span>
        </span>
        <router-link :to="{ name: 'forgotpassword' }" style="color:#2595ff">Forgot Your Password?</router-link>
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
      title: "Register",
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
            this.register(values)
              .then(() => {
                this.$router.push({ name: "home" });
              })
              .catch(() => {
              });
          }
        });
      },
      handleConfirmBlur(e) {
        const value = e.target.value;
        this.confirmPassword = this.confirmPassword || !!value;
      },
      compareToFirstPassword(rule, value, callback) {
        const form = this.form;
        if (value && value !== form.getFieldValue("password")) {
          callback("The password and the confirm password do not match.");
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
  .logo {
    display: flex;
    justify-content: center;
  }
  .left-column {
    background: #FF8F00;
    color: #fff;
    text-align: center;
    height: 561px;
    padding-top: 200px;
    font-size: 16px;
  }
  .left-column h2 {
    color: #fff;
    font-size: 28px;
  }
  .link-span {
    background: white;
    padding: 5px 15px;
    border-radius: 5px;
    font-size: 18px;
  }
</style>
