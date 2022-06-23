<template>
  <a-table
    size="middle"
    :bordered="false"
    :columns="columns"
    :dataSource="orders"
    :rowKey="(record) => record.id"
    :loading="loading"
    :pagination="false"
  >
    <strong slot="no" slot-scope="text, record, index">{{ ++index }}</strong>
    <a-tooltip slot="code" slot-scope="text, record">
      <template slot="title">
        See details
      </template>
      <a @click="$emit('view', record.id)">#{{ record.code }}</a>
    </a-tooltip>
    <span slot="total_amount" slot-scope="text">{{ text | currencyVN }}</span>
    <a-tag slot="status" slot-scope="record" color="green">{{ record  }}</a-tag>
  </a-table>
</template>

<script>
  export default {
    props: {
      orders: {
        type: Array,
        required: true,
      },
      loading: {
        type: Boolean,
        default: false,
      },
    },
    computed: {
      columns() {
        const columns = [
          {
            title: "#No",
            scopedSlots: { customRender: "no" },
          },
          {
            title: "Tour ID",
            scopedSlots: { customRender: "code" },
          },
          {
            title: "User",
            dataIndex: "customer_name",
          },
          {
            title: "Tour Name",
            dataIndex: "tour.name",
          },
          {
            title: "Total",
            dataIndex: "total_amount",
            scopedSlots: { customRender: "total_amount" },
          },
          {
            title: "Status",
            dataIndex: "status",
            scopedSlots: { customRender: "status" },
          },
        ];

        return columns;
      },
    },
  };
</script>

<style></style>
