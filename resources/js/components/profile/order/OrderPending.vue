<template>
  <div>
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
      <a-tag slot="status" slot-scope="record" color="cyan">{{ record  }}</a-tag>
      <template slot="action" slot-scope="text, record">
        <a-button size="small" @click="onClickCancel(record.id)">Cancel</a-button>
      </template>
    </a-table>

    <cancel-order ref="cancelForm" :visible="visible" :confirmLoading="confirmLoading" @cancel="cancel" @ok="sendCancelOrder" />
  </div>
</template>

<script>
  import { mapActions } from "vuex";
  import CancelOrder from "@/components/modal/CancelOrder";
  export default {
    components: { CancelOrder },
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
    data() {
      return {
        visible: false,
        orderId: "",
        confirmLoading: false
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
            title: "ID",
            scopedSlots: { customRender: "code" }
          },
          {
            title: "Tour name",
            dataIndex: "tour.name"
          },
          {
            title: "Departure date",
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
            title: "Payment method",
            dataIndex: "payment_method"
          },
          {
            title: "status",
            dataIndex: "status",
            scopedSlots: { customRender: "status" }
          },
          {
            title: "Option",
            scopedSlots: { customRender: "action" }
          }
        ];

        return columns;
      }
    },
    methods: {
      ...mapActions("orderUser", ["cancelOrder"]),
      onClickCancel(orderId) {
        this.orderId = orderId;
        this.visible = true;
      },
      cancel() {
        const form = this.$refs.cancelForm.form;
        form.resetFields();
        this.visible = false;
        this.orderId = "";
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
                status: 4
              }
            };
            await this.cancelOrder(order);
            this.confirmLoading = false;
            this.cancel();
          }
        });
      }
    }
  };
</script>

<style></style>
