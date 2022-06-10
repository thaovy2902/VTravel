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
        Xem chi tiết
      </template>
      <a @click="$emit('view', record.id)">#{{ record.code }}</a>
    </a-tooltip>
    <a-tag slot="status" slot-scope="record" color="red">{{ record | statusOrder }}</a-tag>
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
            title: "Mã đặt tour",
            scopedSlots: { customRender: "code" },
          },
          {
            title: "Khách hàng",
            dataIndex: "customer_name",
          },
          {
            title: "Tên tour",
            dataIndex: "tour.name",
          },
          {
            title: "Lý do hủy",
            dataIndex: "reason_cancel",
          },
          {
            title: "Trạng thái",
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
