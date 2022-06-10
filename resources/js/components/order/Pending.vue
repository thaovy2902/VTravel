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
          Xem chi tiết
        </template>
        <a @click="$emit('view', record.id)">#{{ record.code }}</a>
      </a-tooltip>
      <a-tag slot="status" slot-scope="record" :color="colorStatus(record)">{{ record | statusOrder }}</a-tag>
      <span slot="total_amount" slot-scope="text">{{ text | currencyVN }}</span>
      <template slot="update" slot-scope="record">
        <a-button size="small" style="margin-right:6px" @click="onAccept(record.id)">Chấp nhận</a-button>
        <a-button ref="cancelButton" size="small" type="dashed" @click="onCancel(record.id)">Hủy bỏ</a-button>
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
      status: {
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
            title: "Mã đặt tour",
            scopedSlots: { customRender: "code" }
          },
          {
            title: "Khách hàng",
            dataIndex: "customer_name"
          },
          {
            title: "Tên tour",
            dataIndex: "tour.name"
          },
          {
            title: "Thành tiền",
            dataIndex: "total_amount",
            scopedSlots: { customRender: "total_amount" }
          },
          {
            title: "Trạng thái",
            dataIndex: "status",
            scopedSlots: { customRender: "status" }
          },
          {
            title: "Tùy chọn",
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
          values: { status: 3 }
        };
        this.updateOrder(order);
        this.$emit("retrieveOrder", this.status);
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
                status: 4
              }
            };
            await this.updateOrder(order);
            this.confirmLoading = false;
            this.cancel();
            this.$emit("retrieveOrder", this.status);
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
