<template>
  <div>
    <a-form style="margin: 40px 40px 0;">
      <a-row :gutter="16">
        <a-col :xs="24" :sm="24" :md="6" :lg="6">
          <h3>Thông tin liên hệ</h3>
          <a-divider />
          <description-item title="Họ tên" :content="userInfo.name" />
          <description-item title="Email" :content="userInfo.email" />
          <description-item title="Số điện thoại" :content="userInfo.phone_number" />
          <description-item title="Địa chỉ" :content="userInfo.address" />
        </a-col>
        <a-col v-if="tour" :xs="24" :sm="24" :md="9" :lg="9">
          <h3>Thông tin tour</h3>
          <a-divider />
          <description-item title="Tên tour" :content="tour.name" />
          <description-item title="Loại tour" :content="tour.category_name" />
          <description-item title="Nơi đi" :content="tour.from_place_name" />
          <description-item title="Nơi đến" :content="tour.to_place_name" />
          <description-item title="Phương tiện" :content="tour.transport" />
          <description-item title="Số ngày" :content="tour.number_days | numberDay" />
          <description-item title="Giá người lớn" :content="tour.price_default | currencyVN" />
          <description-item title="Giá trẻ em" :content="tour.price_children | currencyVN" />
          <description-item title="Giá em bé" :content="tour.price_baby | currencyVN" />
        </a-col>
        <a-col :xs="24" :sm="24" :md="9" :lg="9">
          <h3>Thông tin đặt tour</h3>
          <a-divider />
          <description-item title="Ngày khởi hành" :content="orderInfo.date_depart | date" />
          <description-item title="Người lớn" :content="orderInfo.quantity_people | numberPerson" />
          <description-item title="Trẻ em" :content="orderInfo.quantity_children | numberPerson" />
          <description-item title="Em bé" :content="orderInfo.quantity_baby | numberPerson" />
          <description-item title="Mã giảm giá" :content="orderInfo.discount_code" />
          <description-item title="Phần trăm giảm giá" :content="orderInfo.discount_percent | percent" />
          <description-item title="Tổng cộng" :content="orderInfo.total | currencyVN" />
          <description-item title="Giảm giá" :content="orderInfo.discount | currencyVN" />
          <description-item title="Thành tiền" :content="orderInfo.total_amount | currencyVN" />
          <a-form-item label="Ghi chú">
            <a-textarea v-model="note" placeholder="Ghi chú" :autoSize="{ minRows: 2, maxRows: 4 }" />
          </a-form-item>
          <a-form-item>
            <a-button @click="$emit('prevToContact')" :style="{ marginRight: '8px' }">Trước</a-button>
            <a-button type="primary" @click="nextToPayment">Thanh toán</a-button>
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
