<template>
  <a-table
    size="middle"
    :bordered="false"
    :columns="columns"
    :loading="loading"
    :dataSource="orders"
    :rowKey="record => record.id"
    :pagination="false"
  >
    <strong slot="no" slot-scope="text, record, index">{{ ++index }}</strong>
    <a-tooltip slot="code" slot-scope="text, record">
      <template slot="title">
        Xem chi tiết
      </template>
      <a @click="$emit('view', record.id)">#{{ record.code }}</a>
    </a-tooltip>
    <span slot="date_depart" slot-scope="text">{{ text | date }}</span>
    <span slot="total_amount" slot-scope="text">{{ text | currencyVN }}</span>
    <a-tag slot="status" slot-scope="record" color="green">{{ record | statusOrder }}</a-tag>
  </a-table>
</template>

<script>
  import { mapGetters } from "vuex";
  export default {
    props: {
      orders: {
        type: Array,
        default: []
      },
      loading: {
        type: Boolean,
        default: false
      }
    },
    computed: {
      columns() {
        const columns = [
          {
            title: "#No",
            scopedSlots: { customRender: "no" }
          },
          {
            title: "Mã đặt",
            dataIndex: "code",
            scopedSlots: { customRender: "code" }
          },
          {
            title: "Tên tour",
            dataIndex: "tour.name"
          },
          {
            title: "Ngày khởi hành",
            dataIndex: "date_depart",
            scopedSlots: { customRender: "date_depart" }
          },
          {
            title: "Số người",
            dataIndex: "total_people"
          },
          {
            title: "Tổng tiền",
            dataIndex: "total_amount",
            scopedSlots: { customRender: "total_amount" }
          },
          {
            title: "Thanh toán bằng",
            dataIndex: "payment_method"
          },
          {
            title: "Trạng thái",
            dataIndex: "status",
            scopedSlots: { customRender: "status" }
          }
        ];

        return columns;
      }
    }
  };
</script>

<style></style>
