<template>
  <a-row :gutter="{ xs: 8, sm: 16, md: 16, lg: 16 }">
    <a-col :sm="24" :md="12" :xl="6">
      <card-statistic title="Tổng khách hàng" :loading="loadingStatistic">
        <a-tooltip title="Tổng khách hàng" slot="action">
          <a-icon type="info-circle-o" />
        </a-tooltip>
        <div slot="total">
          <span><a-icon type="user" /> {{ countUserTotal }}</span>
        </div>
        <div slot="footer">
          <a-tooltip title="So với tháng trước" slot="action">
            <a-icon :class="countUserDifference > 0 ? 'up' : 'down'" :type="countUserDifference > 0 ? 'caret-up' : 'caret-down'" />
            {{ countUserDifference > 0 ? "+" : "" }}{{ countUserDifference }} khách hàng
          </a-tooltip>
        </div>
      </card-statistic>
    </a-col>
    <a-col :sm="24" :md="12" :xl="6">
      <card-statistic title="Tổng tour" :loading="loadingStatistic">
        <a-tooltip title="Tổng tour" slot="action">
          <a-icon type="info-circle-o" />
        </a-tooltip>
        <div slot="total">
          <span><a-icon type="project" /> {{ countTourTotal }}</span>
        </div>
        <div slot="footer">
          <a-tooltip title="So với tháng trước" slot="action">
            <a-icon :class="countTourDifference > 0 ? 'up' : 'down'" :type="countTourDifference > 0 ? 'caret-up' : 'caret-down'" />
            {{ countTourDifference > 0 ? "+" : "" }}{{ countTourDifference }} tour
          </a-tooltip>
        </div>
      </card-statistic>
    </a-col>
    <a-col :sm="24" :md="12" :xl="6">
      <card-statistic title="Tổng đặt tour" :loading="loadingStatistic">
        <a-tooltip title="Tổng đặt tour" slot="action">
          <a-icon type="info-circle-o" />
        </a-tooltip>
        <div slot="total">
          <span><a-icon type="shopping-cart" /> {{ countOrderTotal }}</span>
        </div>
        <div slot="footer">
          <a-tooltip title="So với tháng trước" slot="action">
            <a-icon :class="countOrderDifference > 0 ? 'up' : 'down'" :type="countOrderDifference > 0 ? 'caret-up' : 'caret-down'" />
            {{ countOrderDifference > 0 ? "+" : "" }}{{ countOrderDifference }} order
          </a-tooltip>
        </div>
      </card-statistic>
    </a-col>
    <a-col :sm="24" :md="12" :xl="6">
      <card-statistic title="Tổng doanh thu" :loading="loadingStatistic">
        <a-tooltip title="Tổng doanh thu" slot="action">
          <a-icon type="info-circle-o" />
        </a-tooltip>
        <div slot="total">
          <span><a-icon type="dollar" /> {{ totalRevenue | currencyVN }}</span>
        </div>
        <div slot="footer">
          <a-tooltip title="So với tháng trước" slot="action">
            <a-icon :class="totalRevenueDifference > 0 ? 'up' : 'down'" :type="totalRevenueDifference > 0 ? 'caret-up' : 'caret-down'" />
            {{ totalRevenueDifference > 0 ? "+" : "" }}{{ totalRevenueDifference | currencyVN }}
          </a-tooltip>
        </div>
      </card-statistic>
    </a-col>
  </a-row>
</template>

<script>
  import { mapGetters, mapActions } from "vuex";
  import CardStatistic from "@/components/card/CardStatistic";

  export default {
    components: { CardStatistic },
    computed: {
      ...mapGetters("dashboard", [
        "countUserTotal",
        "countUserDifference",
        "countTourTotal",
        "countTourDifference",
        "countOrderTotal",
        "countOrderDifference",
        "totalRevenue",
        "totalRevenueDifference",
        "loadingStatistic"
      ])
    },
    created() {
      this.fetchStatistic();
    },
    methods: {
      ...mapActions("dashboard", ["fetchStatistic"])
    }
  };
</script>

<style>
  .up {
    font-size: 16px;
    color: green;
  }
  .down {
    font-size: 16px;
    color: red;
  }
</style>
