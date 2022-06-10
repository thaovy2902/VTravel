<template>
  <div>
    <a-form style="max-width: 500px; margin: 40px auto 0;">
      <a-form-item v-if="disabledButton" :wrapperCol="{ span: 18, offset: 6 }">
        <a-alert message="Bạn cần điền đầy đủ thông tin" />
      </a-form-item>
      <a-form-item label="Họ tên" :labelCol="{ span: 6 }" :wrapperCol="{ span: 18 }">
        <a-input placeholder="Nhập họ tên" v-model="customer.name" />
      </a-form-item>
      <a-form-item label="Email" :labelCol="{ span: 6 }" :wrapperCol="{ span: 18 }">
        <a-input placeholder="Nhập email" v-model="customer.email" />
      </a-form-item>
      <a-form-item label="Số điện thoại" :labelCol="{ span: 6 }" :wrapperCol="{ span: 18 }">
        <a-input placeholder="Nhập số điện thoại" v-model="customer.phone_number" />
      </a-form-item>
      <a-form-item label="Địa chỉ" :labelCol="{ span: 6 }" :wrapperCol="{ span: 18 }">
        <a-input placeholder="Nhập địa chỉ" v-model="customer.address" />
      </a-form-item>
      <a-form-item
        v-if="!verified"
        label="Xác thực"
        :labelCol="{ span: 6 }"
        :wrapperCol="{ span: 18 }"
        extra="Chúng tôi cần xác thực cho lần giao dịch này. Mã code được gửi qua mail của bạn"
        hasFeedback
      >
        <a-row :gutter="16">
          <a-col :span="17">
            <a-input placeholder="Nhập mã code" v-model="verifyCode" />
          </a-col>
          <a-col :span="7">
            <a-button block :loading="loading" @click="send">Gửi mã</a-button>
          </a-col>
        </a-row>
      </a-form-item>
      <a-form-item :wrapperCol="{ span: 18, offset: 6 }">
        <a-button type="primary" :disabled="disabledButton" @click="nextToAccept">Xác nhận</a-button>
      </a-form-item>
    </a-form>
  </div>
</template>

<script>
  import { mapActions, mapGetters, mapState } from "vuex";
  export default {
    data() {
      return {
        customer: {
          name: this.$auth.user.name,
          email: this.$auth.user.email,
          phone_number: this.$auth.user.phone_number,
          address: this.$auth.user.address,
        },
        verifyCode: "",
      };
    },
    computed: {
      ...mapState("orderUser", ["userInfo"]),
      ...mapGetters("orderUser", ["loading", "verified"]),
      disabledButton() {
        const { customer, verifyCode, verified } = this;
        if (verified) {
          return false;
        }
        if (!customer.name || !customer.email || !customer.phone_number || !customer.address || !verifyCode) {
          return true;
        }
        return false;
      },
    },
    methods: {
      ...mapActions("orderUser", ["sendVerifyToMail", "verifyTransaction", "saveUserInfo"]),
      async nextToAccept() {
        const { customer, verifyCode, verified } = this;
        if (!verified) {
          try {
            const payload = {
              email: customer.email,
              verifyCode: verifyCode,
            };
            await this.verifyTransaction(payload);
            this.saveUserInfo(customer);
            this.$emit("nextToAccept");
          } catch (error) {}
        } else {
          this.$emit("nextToAccept");
        }
      },
      send() {
        this.sendVerifyToMail({ email: this.customer.email });
      },
    },
  };
</script>

<style></style>
