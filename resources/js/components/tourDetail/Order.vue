<template>
  <a-card class="shadow-sm" title="Đặt tour" :bordered="false" :bodyStyle="{ padding: '16px' }">
    <a-row class="mb-2" type="flex" justify="space-between" align="middle">
      <a-col>
        Ngày khởi hành
      </a-col>
      <a-col :span="12">
        <a-date-picker
          placeholder="Chọn ngày khởi hành"
          :disabledDate="disabledDate"
          @change="onChangDepart"
          style="width: 100%;"
          allowClear
        />
      </a-col>
    </a-row>
    <a-row class="mb-2" :gutter="8" type="flex" justify="space-between" align="middle">
      <a-col>
        <b>{{ quantityPeople }}</b> người lớn x <b>{{ tour.price_default | currencyVN }}</b>
      </a-col>
      <a-col :span="6">
        <a-input-number :min="0" :max="tour.number_persons" v-model="quantityPeople" style="width: 100%;" />
      </a-col>
    </a-row>
    <a-row class="mb-2" :gutter="8" type="flex" justify="space-between" align="middle">
      <a-col>
        <b>{{ quantityChildren }}</b> trẻ em x <b>{{ tour.price_children | currencyVN }}</b>
      </a-col>
      <a-col :span="6">
        <a-input-number :min="0" :max="tour.number_persons" v-model="quantityChildren" style="width: 100%;" />
      </a-col>
    </a-row>
    <a-row :gutter="8" type="flex" justify="space-between" align="middle">
      <a-col>
        <b>{{ quantityBaby }}</b> em bé x <b>{{ tour.price_baby | currencyVN }}</b>
      </a-col>
      <a-col :span="6">
        <a-input-number :min="0" :max="quantityPeople" v-model="quantityBaby" style="width: 100%;" />
      </a-col>
    </a-row>
    <a-divider />
    <div :style="{ margin: '12px 0' }">
      Tổng cộng: <b v-if="total !== 0">{{ total | currencyVN }}</b>
    </div>
    <div :style="{ margin: '12px 0' }">
      Giảm giá: <b v-if="discount !== 0">-{{ discount | currencyVN }} (-{{ discountCode.percent }}%)</b>
    </div>
    <a-row :style="{ margin: '12px 0' }" :gutter="8" type="flex" align="middle">
      <a-col :style="{ padding: 0 }" :span="6">
        Mã giảm giá
      </a-col>
      <a-col :span="12">
        <a-input placeholder="Mã giảm giá" v-model="code" :disabled="discount !== 0" />
      </a-col>
      <a-col :span="6">
        <a-button block :disabled="!code" @click="discount === 0 ? getDiscount(code) : cancelDiscount()">
          {{ discount === 0 ? "Áp dụng" : "Hủy" }}
        </a-button>
      </a-col>
    </a-row>
    <div :style="{ fontSize: '18px', fontWeight: 500, margin: '12px 0 24px' }">
      Thành tiền: <b v-if="total !== 0">{{ totalAmount() | currencyVN }}</b>
    </div>
    <div v-if="!$auth.user && !$auth.loggedIn">
      <a-alert type="warning" showIcon style="width: 100%;" banner>
        <div slot="message">Vui lòng <router-link :to="{ name: 'login' }">đăng nhập</router-link> để đặt tour</div>
      </a-alert>
    </div>
    <a-button v-else type="primary" size="large" block @click="continueOrder">Tiếp tục</a-button>
  </a-card>
</template>

<script>
  import { mapActions, mapState } from "vuex";
  export default {
    props: {
      tour: {
        type: Object,
      },
    },
    data() {
      return {
        dateDepart: null,
        quantityPeople: 0,
        quantityChildren: 0,
        quantityBaby: 0,
        //
        code: "",
      };
    },
    computed: {
      ...mapState("orderUser", ["discountCode"]),
      discount() {
        const { total, discountCode } = this;
        return (total / 100) * discountCode.percent || 0;
      },
      total() {
        return this.computePricePeople() + this.computePriceChildren() + this.computePriceBaby();
      },
    },
    methods: {
      ...mapActions("orderUser", ["saveOrderInfo", "applyDiscount", "clearDiscount"]),
      onChangDepart(dates, dateStrings) {
        this.dateDepart = dateStrings;
      },
      disabledDate(current) {
        return current && current < new Date(Date.now() - 864e5);
      },
      computePricePeople() {
        return +this.tour.price_default * this.quantityPeople;
      },
      computePriceChildren() {
        return +this.tour.price_children * this.quantityChildren;
      },
      computePriceBaby() {
        return +this.tour.price_baby * this.quantityBaby;
      },
      totalAmount() {
        return this.total - this.discount;
      },
      getDiscount(code) {
        this.applyDiscount({ discountCode: code });
      },
      cancelDiscount() {
        this.code = "";
        this.clearDiscount();
      },
      continueOrder() {
        const { tour, dateDepart, quantityPeople, quantityChildren, quantityBaby, discountCode, total, discount } = this;
        if (dateDepart == null) {
          this.$message.warning("Vui lòng chọn ngày khởi hành");
          return;
        }
        if (quantityPeople === 0) {
          this.$message.warning("Vui lòng đăng ký ít nhất 1 người lớn");
          return;
        }
        if (quantityPeople + quantityChildren + quantityBaby > tour.number_persons) {
          this.$message.warning(`Số người tối đa vượt quá ${tour.number_persons}`);
          return;
        }
        const data = {
          tour_id: tour.id,
          date_depart: dateDepart,
          quantity_people: quantityPeople,
          quantity_children: quantityChildren,
          quantity_baby: quantityBaby,
          discount_code: discountCode.code || "",
          discount_percent: discountCode.percent || "",
          total: total,
          discount: discount,
          total_amount: this.totalAmount(),
        };
        this.saveOrderInfo(data);
      },
    },
  };
</script>

<style></style>
