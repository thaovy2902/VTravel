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
        See details
      </template>
      <a @click="$emit('view', record.id)">#{{ record.code }}</a>
    </a-tooltip>
    <span slot="date_depart" slot-scope="text">{{ text | date }}</span>
    <span slot="total_amount" slot-scope="text">{{ text | currencyVN }}</span>
    <a-tag slot="status" slot-scope="record" color="green">{{ record }}</a-tag>
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
            title: "ID",
            dataIndex: "code",
            scopedSlots: { customRender: "code" }
          },
          {
            title: "Tour Name",
            dataIndex: "tour.name"
          },
          {
            title: "Departure Date",
            dataIndex: "date_depart",
            scopedSlots: { customRender: "date_depart" }
          },
          {
            title: "Number of Participants",
            dataIndex: "total_people"
          },
          {
            title: "Total",
            dataIndex: "total_amount",
            scopedSlots: { customRender: "total_amount" }
          },
          {
            title: "Payment Method",
            dataIndex: "payment_method"
          },
          {
            title: "Status",
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
