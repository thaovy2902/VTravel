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
    <a-tag slot="Status" slot-scope="record" color="red">{{ record | StatusOrder }}</a-tag>
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
            title: "Tên tour",
            dataIndex: "tour.name"
          },
          {
            title: "Lý do hủy",
            dataIndex: "reason_cancel"
          },
          {
            title: "Status",
            dataIndex: "Status",
            scopedSlots: { customRender: "Status" }
          }
        ];

        return columns;
      }
    }
  };
</script>

<style></style>
