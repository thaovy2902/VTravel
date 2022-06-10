<template>
  <a-card :bordered="false" :body-style="{ padding: '24px' }">
    <a-tabs defaultActiveKey="1" @change="onChangeTab">
      <div slot="tabBarExtraContent">
        <a-button icon="reload" @click="reset">Tải lại</a-button>
        <a-button style="margin-left:8px" :icon="!isSearch ? 'filter' : 'close'" @click="isSearch = !isSearch">
          {{ !isSearch ? "Lọc" : "Đóng" }}
        </a-button>
        <a-range-picker v-if="isSearch" style="margin:0 8px" @change="onChangeRangePicker" />
        <a-input-search v-if="isSearch" placeholder="Tìm kiếm theo mã đặt" allowClear @search="search" :style="{ width: '200px' }" />
      </div>
      <a-tab-pane tab="Đang xử lý" key="1">
        <pending status="1" :orders="orders" :loading="loading" @retrieveOrder="retrieveOrder" @view="viewDetail" />
      </a-tab-pane>
      <a-tab-pane tab="Chưa thanh toán" key="2">
        <pending status="2" :orders="orders" :loading="loading" @retrieveOrder="retrieveOrder" @view="viewDetail" />
      </a-tab-pane>
      <a-tab-pane tab="Thành công" key="3">
        <successful :orders="orders" :loading="loading" @view="viewDetail" />
      </a-tab-pane>
      <a-tab-pane tab="Bị hủy" key="4">
        <canceled :orders="orders" :loading="loading" @view="viewDetail" />
      </a-tab-pane>
    </a-tabs>

    <pagination-table
      :total="pagination.total"
      :pageSize="pagination.perPage"
      :current="pagination.current"
      @onShowSizeChange="onShowSizeChange"
      @onChange="onChange"
    />

    <order-detail v-if="order" :visible-preview="visiblePreview" :data="order" @close="closeDetail" />
  </a-card>
</template>

<script>
  import { mapGetters, mapActions } from "vuex";
  import { cleanAccents } from "@/helpers/tools";
  import Pending from "@/components/order/Pending";
  import Successful from "@/components/order/Successful";
  import Canceled from "@/components/order/Canceled";
  import PaginationTable from "@/components/pagination/PaginationTable";
  import OrderDetail from "@/components/orderDetail/OrderDetail";
  export default {
    components: { Pending, Successful, Canceled, PaginationTable, OrderDetail },
    metaInfo: {
      title: "Đặt tour",
    },
    data() {
      return {
        pagination: {
          total: 0,
          perPage: 0,
          current: 1
        },
        ordersStatus: 1,
        date: {
          from: "",
          to: ""
        },
        q: "",
        //
        order: {},
        visiblePreview: false,
        //
        isSearch: false
      };
    },
    computed: {
      ...mapGetters("order", ["orders", "getOrderById", "loading"])
    },
    created() {
      this.fetchData();
    },
    methods: {
      ...mapActions("order", ["fetchOrders"]),
      async fetchData(params = { status: this.ordersStatus }) {
        const pagination = { ...this.pagination };
        const { data } = await this.fetchOrders(params);
        pagination.total = data.meta.total;
        pagination.perPage = data.meta.per_page;
        pagination.current = data.meta.current_page;
        this.pagination = pagination;
      },
      onShowSizeChange({ current, pageSize }) {
        const page = current;
        const perPage = pageSize;
        const status = this.ordersStatus;
        const from_date = this.date.from;
        const to_date = this.date.to;
        this.fetchData({ page, perPage, status, from_date, to_date });
      },
      onChange({ page, pageSize }) {
        const perPage = pageSize;
        const status = this.ordersStatus;
        const from_date = this.date.from;
        const to_date = this.date.to;
        this.fetchData({ page, perPage, status, from_date, to_date });
      },
      onChangeRangePicker(dates, dateStrings) {
        const from_date = dateStrings[0];
        const to_date = dateStrings[1];
        const pager = { ...this.pagination };
        const page = pager.current;
        const perPage = pager.perPage;
        const status = this.ordersStatus;
        this.fetchData({ page, perPage, status, from_date, to_date });
        this.date.from = from_date;
        this.date.to = to_date;
      },
      search(value) {
        if (value) {
          const pager = { ...this.pagination };
          const page = pager.current;
          const perPage = pager.perPage;
          const status = this.ordersStatus;
          const from_date = this.date.from;
          const to_date = this.date.to;
          const q = cleanAccents(value);
          this.fetchData({ page, perPage, status, from_date, to_date, q });
          this.q = q;
        }
      },
      reset() {
        this.fetchData();
        this.date.from = "";
        this.date.to = "";
        this.q = "";
      },
      onChangeTab(activeKey) {
        const status = +activeKey;
        const { current, perPage } = this.pagination;
        const page = current;
        const from_date = this.date.from;
        const to_date = this.date.to;
        this.fetchData({ page, perPage, status, from_date, to_date });
        this.ordersStatus = status;
      },
      retrieveOrder(status) {
        this.onChangeTab(status);
      },
      //
      viewDetail(orderId) {
        this.order = this.getOrderById(orderId);
        this.visiblePreview = true;
      },
      closeDetail() {
        this.order = {};
        this.visiblePreview = false;
      }
    }
  };
</script>

<style></style>
