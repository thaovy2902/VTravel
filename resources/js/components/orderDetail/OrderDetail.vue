<template>
  <a-drawer :width="width" :visible="visiblePreview" @close="$emit('close')">
    <div class="preview-title">
      Order #{{ data.code }}
      <a-tag :color="getColor(data.status)">{{ data.status  }}</a-tag>
    </div>
    <div v-if="data.status === 4">
      <p :style="pStyle">Reason</p>
      <a-row>
        <a-col :span="24">
          <description-item title="Reason" :content="data.reason_cancel" />
        </a-col>
      </a-row>
      <a-divider />
    </div>

    <p :style="pStyle">Contact Information</p>
    <a-row>
      <a-col :span="12">
        <description-item title="Full Name" :content="data.customer_name" />
        <description-item title="Phone Number" :content="data.customer_phone_number" />
      </a-col>
      <a-col :span="12">
        <description-item title="Email" :content="data.customer_email" />
        <description-item title="Address" :content="data.customer_address" />
      </a-col>
    </a-row>

    <a-divider />

    <p :style="pStyle">Tour Information</p>
    <a-row v-if="data.tour">
      <a-col :span="12">
        <description-item title="Tour ID" :content="`#${data.tour.code}`" />
        <description-item title="Tour name" :content="data.tour.name" />
        <description-item title="Origin" :content="data.tour.from_place_name" />
        <description-item title="Time" :content="data.tour.number_days | numberDay" />
        <description-item title="Transport" :content="data.tour.transport" />
        <description-item title="Giá trẻ em" :content="data.tour.price_children | currencyVN" />
      </a-col>
      <a-col :span="12">
        <description-item title="Category" :content="data.tour.category_name" />
        <description-item title="Departure" :content="data.tour.depart" />
        <description-item title="Destination" :content="data.tour.to_place_name" />
        <description-item title="Số người" :content="data.tour.number_persons | numberPerson" />
        <description-item title="Giá người lớn" :content="data.tour.price_default | currencyVN" />
        <description-item title="Giá em bé" :content="data.tour.price_baby | currencyVN" />
      </a-col>
    </a-row>

    <a-divider />

    <p :style="pStyle">Thông tin đặt tour</p>
    <a-row>
      <a-col :span="12">
        <description-item title="Ngày đặt" :content="data.created_at | dateTime" />
        <description-item title="Ngày khởi hành" :content="data.date_depart | date" />
        <description-item title="Em bé" :content="data.quantity_baby | numberPerson" />
      </a-col>
      <a-col :span="12">
        <description-item title="Thanh toán bằng" :content="data.payment_method" />
        <description-item title="Người lớn" :content="data.quantity_people | numberPerson" />
        <description-item title="Trẻ em" :content="data.quantity_children | numberPerson" />
      </a-col>
      <a-col :span="12">
        <description-item title="Mã giảm giá" :content="data.discount_code" />
        <description-item title="Tổng cộng" :content="data.total | currencyVN" />
      </a-col>
      <a-col :span="12">
        <description-item title="Phần trăm giảm giá" :content="data.discount_percent | percent" />
        <description-item title="Giảm giá" :content="data.discount | currencyVN" />
      </a-col>
      <a-col :span="24">
        <description-item title="Thành tiền" :content="data.total_amount | currencyVN" />
        <description-item title="Ghi chú" :content="data.note" />
      </a-col>
    </a-row>
  </a-drawer>
</template>

<script>
  import DescriptionItem from "@/components/description/DescriptionItem";
  import { getColorStatusOrder } from "@/helpers/tools";
  export default {
    components: { DescriptionItem },
    props: {
      visiblePreview: {
        type: Boolean,
        default: false
      },
      width: {
        type: Number,
        default: 640
      },
      data: {
        type: Object,
        default: {}
      }
    },
    data() {
      return {
        pStyle: {
          fontSize: "16px",
          color: "rgba(0,0,0,0.85)",
          lineHeight: "24px",
          display: "block",
          marginBottom: "16px"
        }
      };
    },
    methods: {
      getColor(val) {
        return getColorStatusOrder(val);
      }
    }
  };
</script>

<style lang="less">
  .preview-title {
    font-size: 16px;
    font-weight: 700;
    color: rgba(0, 0, 0, 0.85);
    line-height: 24px;
    display: block;
    margin-bottom: 16px;
  }
</style>
