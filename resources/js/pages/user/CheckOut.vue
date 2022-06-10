<template>
  <div :style="{ marginTop: '24px' }">
    <a-card :bordered="false" :bodyStyle="{ padding: '16px' }">
      <a-button icon="arrow-left" @click="$router.push({ name: 'tours.show', params: { slug: orderInfo.slug } })">Quay lại</a-button>
      <a-divider />
      <a-steps :current="current" class="steps">
        <a-step v-for="item in steps" :key="item.title" :title="item.title">
          <a-icon :type="item.icon" slot="icon" />
        </a-step>
      </a-steps>
      <div>
        <contact v-if="current === 0" @nextToAccept="nextToAccept" />
        <accept v-if="current === 1" @prevToContact="prevToContact" @nextToPayment="nextToPayment" />
        <payment v-if="current === 2" :is-loading="loading" @prevToAccept="prevToAccept" @finishOrder="finishOrder" />
        <finish v-if="current === 3" />
      </div>
    </a-card>
  </div>
</template>

<script>
  import { mapState, mapGetters, mapActions } from "vuex";
  import Contact from "@/components/checkout/Contact";
  import Payment from "@/components/checkout/Payment";
  import Accept from "@/components/checkout/Accept";
  import Finish from "@/components/checkout/Finish";
  export default {
    components: { Contact, Payment, Accept, Finish },
    metaInfo: {
      title: "Đặt tour",
    },
    props: {
      tour: {
        type: Object,
      },
    },
    data() {
      return {
        steps: [
          {
            title: "Thông tin liên hệ",
            icon: "user",
          },
          {
            title: "Xác nhận thông tin",
            icon: "unordered-list",
          },
          {
            title: "Phương thức thanh toán",
            icon: "pay-circle",
          },
          {
            title: "Hoàn thành",
            icon: "check-circle",
          },
        ],
        note: "",
      };
    },
    watch: {
      $route: {
        handler: "checkToken",
        immediate: true,
      },
    },
    computed: {
      ...mapState("orderUser", ["orderInfo", "userInfo"]),
      ...mapGetters("orderUser", ["loading"]),
      current() {
        return +this.$route.query.step || 0;
      },
    },
    methods: {
      ...mapActions("orderUser", ["createOrder", "checkToken"]),
      nextToAccept() {
        this.$router.push({ query: { step: 1, token: this.orderInfo.token } });
      },
      nextToPayment(note) {
        this.note = note;
        this.$router.push({ query: { step: 2, token: this.orderInfo.token } });
      },
      prevToContact() {
        this.$router.push({ query: { step: 0, token: this.orderInfo.token } });
      },
      prevToAccept() {
        this.$router.push({ query: { step: 1, token: this.orderInfo.token } });
      },
      async finishOrder(paymentId) {
        const { userInfo, orderInfo, note } = this;
        const order = {
          customer_name: userInfo.name,
          customer_email: userInfo.email,
          customer_phone_number: userInfo.phone_number,
          customer_address: userInfo.address,
          date_depart: orderInfo.date_depart,
          quantity_people: orderInfo.quantity_people,
          quantity_children: orderInfo.quantity_children,
          quantity_baby: orderInfo.quantity_baby,
          discount_code: orderInfo.discount_code,
          discount_percent: orderInfo.discount_percent,
          total: orderInfo.total,
          discount: orderInfo.discount,
          total_amount: orderInfo.total_amount,
          note: note,
          is_paid: paymentId === 2 ?? false,
          payment_id: paymentId,
          tour_id: orderInfo.tour_id,
        };
        try {
          await this.createOrder(order);
          this.$router.push({ query: { step: 3, token: orderInfo.token } });
        } catch (error) {}
      },
    },
  };
</script>

<style scoped>
  .steps {
    max-width: 1100px;
    margin: 16px auto;
  }
</style>
