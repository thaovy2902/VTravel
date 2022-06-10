<template>
  <div>
    <a-card class="shadow-sm" :bordered="false" :bodyStyle="{ padding: '16px' }">
      <a-tabs :defaultActiveKey="$route.query.status" @change="onChangeTab">
        <div slot="tabBarExtraContent">
          <a-input-search placeholder="Tìm kiếm theo mã đặt tour" allowClear @search="onSearch" style="width:220px" />
        </div>
        <a-tab-pane tab="Đang xử lý" key="1">
          <order-pending :orders="orders" :loading="loading" @view="viewDetail" />
        </a-tab-pane>
        <a-tab-pane tab="Chờ thanh toán" key="2">
          <order-unpaid :orders="orders" :loading="loading" @view="viewDetail" />
        </a-tab-pane>
        <a-tab-pane tab="Thành công" key="3">
          <order-successful :orders="orders" :loading="loading" @view="viewDetail" />
        </a-tab-pane>
        <a-tab-pane tab="Đã hủy" key="4">
          <order-canceled :orders="orders" :loading="loading" @view="viewDetail" />
        </a-tab-pane>
      </a-tabs>
      <a-row type="flex" justify="center" align="middle" style="marginTop:32px">
        <a-col v-if="pagination.total > 0">
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
    </a-card>

    <order-detail v-if="order" :visible-preview="visiblePreview" :data="order" @close="closeDetail" />
  </div>
</template>

<script>
  import { mapGetters, mapActions } from "vuex";
  import OrderPending from "./order/OrderPending";
  import OrderUnpaid from "./order/OrderUnpaid";
  import OrderSuccessful from "./order/OrderSuccessful";
  import OrderCanceled from "./order/OrderCanceled";
  import OrderDetail from "@/components/orderDetail/OrderDetail";
  export default {
    components: { OrderPending, OrderUnpaid, OrderSuccessful, OrderCanceled, OrderDetail },
    metaInfo: {
      title: "Lịch sử giao dịch",
    },
    data() {
      return {
        pagination: {
          total: 0,
          current_page: 1,
          per_page: 9,
        },
        query: {
          page: 1,
          q: "",
          status: 0,
        },
        //
        order: {},
        visiblePreview: false,
      };
    },
    computed: {
      ...mapGetters("orderUser", ["orders", "getOrderById", "loading"]),
      columns() {
        const columns = [
          {
            title: "#No",
            customRender: (text, record, index) => ++index,
          },
          {
            title: "Mã đặt",
            dataIndex: "code",
          },
          {
            title: "Tên tour",
            dataIndex: "tour_name",
          },
          {
            title: "Ngày khởi hành",
            dataIndex: "date_depart",
          },
          {
            title: "Số lượng người",
            dataIndex: "total_people",
          },
          {
            title: "Tổng tiền",
            dataIndex: "total_amount",
          },
          {
            title: "Thanh toán bằng",
            dataIndex: "payment_method",
          },
        ];

        return columns;
      },
    },
    created() {
      this.fetchData();
    },
    watch: {
      $route: "fetchData",
    },
    methods: {
      ...mapActions("orderUser", ["fetchOrders"]),
      async fetchData() {
        const pagination = { ...this.pagination };
        const { total, current_page, per_page } = await this.fetchOrders();
        pagination.total = total;
        pagination.current_page = current_page;
        pagination.per_page = per_page;
        this.pagination = pagination;
      },
      onChangePage(page) {
        this.query.page = page;
        this.hanldeChangeRoute();
      },
      onSearch(value) {
        this.query.q = value;
        this.hanldeChangeRoute();
      },
      onChangeTab(activeKey) {
        this.query.status = +activeKey;
        this.hanldeChangeRoute();
      },
      hanldeChangeRoute() {
        const query = { ...this.query };
        let sendQuery = {};
        if (query.page) sendQuery.page = query.page;
        if (query.q) sendQuery.q = query.q;
        if (query.status) sendQuery.status = query.status;

        this.$router.push({ query: { ...sendQuery } });
        this.query = sendQuery;
      },
      viewDetail(orderId) {
        this.order = this.getOrderById(orderId);
        this.visiblePreview = true;
      },
      closeDetail() {
        this.order = {};
        this.visiblePreview = false;
      },
    },
  };
</script>

<style></style>
