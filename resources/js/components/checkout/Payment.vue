<template>
  <div>
    <a-form style="max-width: 500px; margin: 40px auto 0;">
      <a-form-item label="Payment Method" :labelCol="{ span: 8 }" :wrapperCol="{ span: 16 }">
        <a-select
          placeholder="Choose payment method"
          :defaultValue="$route.query.vnp_ResponseCode ? 2 : undefined"
          @change="onChangePaymentMethod"
        >
          <a-select-option v-for="payment in payments" :key="payment.id" :value="payment.id">{{ payment.payment_method }}</a-select-option>
        </a-select>
      </a-form-item>
      <a-form-item v-if="paymentSelected === 2">
        <h3>Online Payment VNPay</h3>
        <description-item title="Total Payment" :content="orderInfo.total_amount | currencyVN" />
        <div v-if="!isPaid" @click="vnpay" style="cursor: pointer;">
          <img class="ncb-logo" src="https://interloan.vn/static/images/ncb.png" alt="ncb_logo" />
          National Citizen Bank (NCB)
        </div>
        <div v-if="$route.query.vnp_ResponseCode" style="margin: 12px 0;">
          <div v-if="paymentOnlineStatus === '00'">
            <a-alert message="Payment Successful!" type="success" showIcon closable />
          </div>
          <div v-if="paymentOnlineStatus !== '00'">
            <a-alert message="Payment Failed" type="error" showIcon closable />
          </div>
        </div>
      </a-form-item>
      <a-form-item :wrapperCol="{ span: 16, offset: 8 }">
        <a-button @click="$emit('prevToAccept')" style="margin-right: 8px;">Back</a-button>
        <a-button type="primary" :loading="isLoading" :disabled="disabledFinishButton" @click="finishOrder">Complete</a-button>
      </a-form-item>
    </a-form>
  </div>
</template>

<script>
  import { mapState, mapActions } from "vuex";
  import DescriptionItem from "@/components/description/DescriptionItem";
  export default {
    components: { DescriptionItem },
    props: {
      isLoading: {
        type: Boolean,
        default: false,
      },
    },
    data() {
      return {
        paymentSelected: this.$route.query.vnp_ResponseCode ? 2 : undefined || undefined,
        isPaid: false,
      };
    },
    computed: {
      ...mapState("orderUser", ["orderInfo", "payments"]),
      disabledFinishButton() {
        const { paymentSelected, isPaid } = this;
        if (paymentSelected === undefined) {
          return true;
        }
        if (paymentSelected === 2 && !isPaid) {
          return true;
        }
        return false;
      },
      paymentOnlineStatus() {
        const resCode = this.$route.query.vnp_ResponseCode || "404";
        if (resCode === "00") {
          this.isPaid = true;
        }
        return resCode;
      },
    },
    mounted() {
      this.fetchPayments();
    },
    methods: {
      ...mapActions("orderUser", ["fetchPayments"]),
      onChangePaymentMethod(value) {
        this.paymentSelected = value;
      },
      finishOrder() {
        this.$emit("finishOrder", this.paymentSelected);
      },
      vnpay() {
        const { orderInfo } = this;
        const payload = {
          token: orderInfo.token,
          total_amount: orderInfo.total_amount,
        };
        axios.post("create-vnpay", payload).then(({ data }) => {
          const a = document.createElement("a");
          a.href = data;
          a.click();
        });
      },
    },
  };
</script>

<style scoped>
  .ncb-logo {
    margin: 12px 12px 12px 0;
    box-shadow: 0 1px 6px 0 rgba(0, 0, 0, 0.2);
  }
</style>
