<template>
  <div>
    <a-table
      size="middle"
      :bordered="false"
      :columns="columns"
      :dataSource="orders"
      :rowKey="record => record.id"
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
      <a-tag slot="Status" slot-scope="record" :color="colorStatus(record)">{{ record | StatusOrder }}</a-tag>
      <span slot="total_amount" slot-scope="text">{{ text | currencyVN }}</span>
      <template slot="update" slot-scope="record">
        <a-button size="small" style="margin-right:6px" @click="onAccept(record.id)">Approve</a-button>
        <a-button ref="cancelButton" size="small" type="dashed" @click="onCancel(record.id)">Reject</a-button>
      </template>
    </a-table>

    <cancel-order ref="cancelForm" :visible="visible" :confirmLoading="confirmLoading" @cancel="cancel" @ok="sendCancelOrder" />
  </div>
</template>

<script>
  import { mapActions } from "vuex";
  import { getColorStatusOrder } from "@/helpers/tools";
  import CancelOrder from "@/components/modal/CancelOrder";
  export default {
    components: { CancelOrder },
    props: {
      Status: {
        type: String
      },
      orders: {
        type: Array,
        required: true
      },
      loading: {
        type: Boolean,
        default: false
      }
    },
    data() {
      return {
        visible: false,
        confirmLoading: false,
        orderId: ""
      };
    },
    computed: {
      columns() {
        const columns = [
          {
            title: "#No",
            scopedSlots: { customRender: "no" }
          },
          {
            title: "Tour ID",
            scopedSlots: { customRender: "code" }
          },
          {
            title: "User",
            dataIndex: "customer_name"
          },
          {
            title: "Tour name",
            dataIndex: "tour.name"
          },
          {
            title: "Total",
            dataIndex: "total_amount",
            scopedSlots: { customRender: "total_amount" }
          },
          {
            title: "Status",
            dataIndex: "Status",
            scopedSlots: { customRender: "Status" }
          },
          {
            title: "Option",
            align: "center",
            scopedSlots: { customRender: "update" }
          }
        ];

        return columns;
      }
    },
    methods: {
      ...mapActions("order", ["updateOrder"]),
      onAccept(orderId) {
        const order = {
          id: orderId,
          values: { Status: 3 }
        };
        this.updateOrder(order);
        this.$emit("retrieveOrder", this.Status);
      },
      onCancel(orderId) {
        this.visible = true;
        this.orderId = orderId;
      },
      sendCancelOrder() {
        const form = this.$refs.cancelForm.form;
        form.validateFields(async (err, values) => {
          if (!err) {
            this.confirmLoading = true;
            const order = {
              id: this.orderId,
              values: {
                reason_cancel: values.reason_cancel,
                Status: 4
              }
            };
            await this.updateOrder(order);
            this.confirmLoading = false;
            this.cancel();
            this.$emit("retrieveOrder", this.Status);
          }
        });
      },
      cancel() {
        const form = this.$refs.cancelForm.form;
        form.resetFields();
        this.visible = false;
        this.orderId = "";
      },
      colorStatus(val) {
        return getColorStatusOrder(val);
      }
    }
  };
</script>

<style></style>
