<template>
  <div>
    <card-table placeholder="Search by Tour ID" :title="title" @reset="reset" @search="search">
      <a-table
        size="middle"
        :columns="columns"
        :loading="loadingNewOrder"
        :rowKey="record => record.id"
        :dataSource="newOrders"
        :pagination="false"
      >
        <strong slot="no" slot-scope="text, record, index">{{ ++index }}</strong>
        <a-tooltip slot="code" slot-scope="text, record">
          <template slot="title">
            See details
          </template>
          <a @click="onClickDetail(record.id)">#{{ record.code }}</a>
        </a-tooltip>
        <span slot="tour_code" slot-scope="text">#{{ text }}</span>
        <span slot="total_amount" slot-scope="text">{{ text | currencyVN }}</span>
        <a-tag slot="Status" slot-scope="record" :color="getColorStatusOrder(record)">{{ record | StatusOrder }}</a-tag>
        <template v-if="record.Status === 1 || record.Status === 2" slot="update" slot-scope="record">
          <a-button size="small" style="margin-right:6px" @click="onAccept(record.id)">Approve</a-button>
          <a-button ref="cancelButton" size="small" type="dashed" @click="onCancel(record.id)">Reject</a-button>
        </template>
      </a-table>

      <a-row v-if="pagination.total > 0" type="flex" justify="center" align="middle" style="margin-top:12px">
        <a-col>
          <a-pagination
            size="small"
            showQuickJumper
            :current="pagination.current_page"
            :pageSize="pagination.per_page"
            :total="pagination.total"
            :showTotal="(total, range) => `${range[0]}-${range[1]} of ${total} items`"
            @change="onChangePage"
          />
        </a-col>
      </a-row>
    </card-table>

    <order-detail v-if="order" :visible-preview="visiblePreview" :data="order" @close="closeDetail" />
    <cancel-order ref="cancelForm" :visible="visible" :confirmLoading="confirmLoading" @cancel="cancel" @ok="sendCancelOrder" />
  </div>
</template>

<script>
  import { mapGetters, mapActions } from "vuex";
  import { cleanAccents, getColorStatusOrder } from "@/helpers/tools";
  import CardTable from "@/components/card/CardTable";
  import OrderDetail from "@/components/orderDetail/OrderDetail";
  import CancelOrder from "@/components/modal/CancelOrder";
  export default {
    components: { CardTable, OrderDetail, CancelOrder },
    data() {
      return {
        title: "Orders Today",
        pagination: {
          total: 0,
          per_page: 0,
          current_page: 1
        },
        q: "",
        order: {},
        visiblePreview: false,
        //
        visible: false,
        confirmLoading: false,
        orderId: ""
      };
    },
    computed: {
      ...mapGetters("dashboard", ["loadingNewOrder", "newOrders", "getOrderById"]),
      columns() {
        const columns = [
          {
            title: "#No",
            scopedSlots: { customRender: "no" }
          },
          {
            title: "#ID",
            scopedSlots: { customRender: "code" }
          },
          {
            title: "Customer",
            dataIndex: "customer_name"
          },
          {
            title: "Tour ID",
            dataIndex: "tour.code",
            scopedSlots: { customRender: "tour_code" }
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
    created() {
      this.fetchData();
    },
    methods: {
      ...mapActions("dashboard", ["fetchNewOrder", "updateStatusOrder"]),
      async fetchData(params = {}) {
        const pagination = { ...this.pagination };
        const { total, per_page, current_page } = await this.fetchNewOrder(params);
        pagination.total = total;
        pagination.per_page = per_page;
        pagination.current_page = current_page;
        this.pagination = pagination;
      },
      onChangePage(page) {
        this.fetchData({ page });
      },
      reset() {
        this.fetchData({ page: 1 });
        this.q = "";
      },
      search(value) {
        if (value) {
          const pager = { ...this.pagination };
          const q = cleanAccents(value);
          this.fetchData({ page: pager.current_page, q });
          this.q = q;
        }
      },
      getColorStatusOrder(val) {
        return getColorStatusOrder(val);
      },
      onClickDetail(orderId) {
        const order = this.getOrderById(orderId);
        this.order = order;
        this.visiblePreview = true;
      },
      closeDetail() {
        this.order = {};
        this.visiblePreview = false;
      },
      onAccept(orderId) {
        const order = {
          id: orderId,
          values: { Status: 3 }
        };
        this.updateStatusOrder(order);
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
            await this.updateStatusOrder(order);
            this.confirmLoading = false;
            this.cancel();
          }
        });
      },
      cancel() {
        const form = this.$refs.cancelForm.form;
        form.resetFields();
        this.visible = false;
        this.orderId = "";
      }
    }
  };
</script>

<style></style>
