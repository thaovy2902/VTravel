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
    <a-tag slot="status" slot-scope="record" color="red">{{ record  }}</a-tag>
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
            title: "Reason",
            dataIndex: "reason_cancel"
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
