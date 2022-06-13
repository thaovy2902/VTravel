<template>
  <div>
    <a-form style="margin: 40px 40px 0;">
      <a-row :gutter="16">
        <a-col :xs="24" :sm="24" :md="6" :lg="6">
          <h3>Contact information</h3>
          <a-divider />
          <description-item title="Full name" :content="userInfo.name" />
          <description-item title="Email" :content="userInfo.email" />
          <description-item title="Phone number" :content="userInfo.phone_number" />
          <description-item title="Address" :content="userInfo.address" />
        </a-col>
        <a-col v-if="tour" :xs="24" :sm="24" :md="9" :lg="9">
          <h3>Tour information</h3>
          <a-divider />
          <description-item title="Tour name" :content="tour.name" />
          <description-item title="Category" :content="tour.category_name" />
          <description-item title="Origin" :content="tour.from_place_name" />
          <description-item title="Destination" :content="tour.to_place_name" />
          <description-item title="Transport" :content="tour.transport" />
          <description-item title="Time" :content="tour.number_days | numberDay" />
          <description-item title="Adults" :content="tour.price_default | currencyVN" />
          <description-item title="Children (2-12)" :content="tour.price_children | currencyVN" />
          <description-item title="Infants (0-2)" :content="tour.price_baby | currencyVN" />
        </a-col>
        <a-col :xs="24" :sm="24" :md="9" :lg="9">
          <h3>Booking information</h3>
          <a-divider />
          <description-item title="Departure Date" :content="orderInfo.date_depart | date" />
          <description-item title="Adults" :content="orderInfo.quantity_people | numberPerson" />
          <description-item title="Children" :content="orderInfo.quantity_children | numberPerson" />
          <description-item title="Infants" :content="orderInfo.quantity_baby | numberPerson" />
          <description-item title="Discount code" :content="orderInfo.discount_code" />
          <description-item title="Phần trăm giảm giá" :content="orderInfo.discount_percent | percent" />
          <description-item title="Subtotal" :content="orderInfo.total | currencyVN" />
          <description-item title="Giảm giá" :content="orderInfo.discount | currencyVN" />
          <description-item title="Total" :content="orderInfo.total_amount | currencyVN" />
          <a-form-item label="Note">
            <a-textarea v-model="note" placeholder="Note" :autoSize="{ minRows: 2, maxRows: 4 }" />
          </a-form-item>
          <a-form-item>
            <a-button @click="$emit('prevToContact')" :style="{ marginRight: '8px' }">Trước</a-button>
            <a-button type="primary" @click="nextToPayment">Pay</a-button>
          </a-form-item>
        </a-col>
      </a-row>
    </a-form>
  </div>
</template>

<script>
  import DescriptionItem from "@/components/description/DescriptionItem";
  import { mapActions, mapGetters, mapState } from "vuex";
  export default {
    components: { DescriptionItem },
    data() {
      return {
        note: "",
      };
    },
    computed: {
      ...mapState("orderUser", ["orderInfo", "userInfo"]),
      ...mapGetters("orderUser", ["tour"]),
    },
    watch: {
      $route: "getTourData",
    },
    created() {
      this.getTourData();
    },
    methods: {
      ...mapActions("orderUser", ["getTourData"]),
      nextToPayment() {
        this.$emit("nextToPayment", this.note);
      },
      finishOrder() {
        this.$emit("finishOrder", this.note);
      },
    },
  };
</script>

<style></style>
