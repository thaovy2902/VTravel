<template>
  <auth-form>
    <div>
      <h2 style="color:#2595ff; text-align: center">ACCOUNT LOGIN</h2>
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
          or
          <router-link :to="{ name: 'register' }">Sign Up</router-link>
        </span>
        <router-link :to="{ name: 'forgotpassword' }" style="color:#2595ff">Forgot Your Password?</router-link>
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
</style>
