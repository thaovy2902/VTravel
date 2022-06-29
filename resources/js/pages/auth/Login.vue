<template>
  <auth-form>
    <a-col :xs="4" :sm="4" :md="9" :lg="9">
            <div class="left-column">
        <div class="left-column-content">
          <h2>WELCOME!</h2>
          <p style="margin-bottom: 5px;">You don't have an account?</p>
          <p style="margin-bottom: 40px;">Please Sign Up from here!</p>
          <span class="link-span">
            <router-link :to="{ name: 'register' }">Sign Up</router-link>
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
      <h2 style="color:#2595ff; text-align: center; margin-top: 20px;">ACCOUNT LOGIN</h2>
      </div>
      <a-form :form="form" @submit.prevent="handleSubmit">
        <a-form-item has-feedback>
          <a-input
            size="large"
            v-decorator="[
              'email',
              {
                rules: [
                  { type: 'email', message: 'The email address you have entered is not valid.' },
                  { required: true, message: 'This filed is required.' }
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
                  { min: 6, message: 'Passwords must be at least 6 characters long.' }
                ]
              }
            ]"
            type="password"
            placeholder="Password"
          >
            <a-icon slot="prefix" type="lock" style="color: rgba(0,0,0,.25)" />
          </a-input>
        </a-form-item>
        <a-form-item>
          <a-button size="large" type="primary" html-type="submit" :loading="loading" block>
            Log In
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
      title: "Log In",
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
            this.login(values).catch(() => {
            });
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
  .logo {
    display: flex;
    justify-content: center;
  }
  .left-column {
    background: #FF8F00;
    color: #fff;
    text-align: center;
    height: 420px;
    padding-top: 100px;
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
