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
    <a-tag slot="status" slot-scope="record" color="red">{{ record | statusOrder }}</a-tag>
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
            title: "Lý do hủy",
            dataIndex: "reason_cancel"
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
