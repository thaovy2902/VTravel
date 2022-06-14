<template>
  <a-card :bordered="false" :bodyStyle="{ padding: '16px' }" :headStyle="{ padding: '0 16px' }">
    <div class="header">
      <h3>Revenue</h3>
      <div>
        <a-select v-if="type === 'month'" v-model="month" placeholder="Choose month" style="width:180px;margin-right:8px">
          <a-select-option value="1">January</a-select-option>
          <a-select-option value="2">February</a-select-option>
          <a-select-option value="3">March</a-select-option>
          <a-select-option value="4">April</a-select-option>
          <a-select-option value="5">May</a-select-option>
          <a-select-option value="6">June</a-select-option>
          <a-select-option value="7">July</a-select-option>
          <a-select-option value="8">August</a-select-option>
          <a-select-option value="9">September</a-select-option>
          <a-select-option value="10">October</a-select-option>
          <a-select-option value="11">November</a-select-option>
          <a-select-option value="12">December</a-select-option>
        </a-select>
        <a-select v-if="type === 'quarter'" v-model="quarter" placeholder="Choose quarter" style="width:180px;margin-right:8px">
          <a-select-option value="1">First quarter</a-select-option>
          <a-select-option value="2">Second quarter</a-select-option>
          <a-select-option value="3">Third quarter</a-select-option>
          <a-select-option value="4">Fourth quarter</a-select-option>
        </a-select>
        <a-select placeholder="Choose year" v-model="year" style="width:180px;margin-right:8px">
          <a-select-option value="2021">2021</a-select-option>
          <a-select-option value="2020">2020</a-select-option>
          <a-select-option value="2019">2019</a-select-option>
        </a-select>

        <a-button type="primary" :disabled="disabledButton" @click="getRevenue">View</a-button>

        <a-radio-group :defaultValue="type" @change="onChangeType" style="margin-left:8px">
          <a-radio-button value="month">Month</a-radio-button>
          <a-radio-button value="quarter">Quarter</a-radio-button>
          <a-radio-button value="year">Year</a-radio-button>
        </a-radio-group>
      </div>
    </div>
    <a-spin :spinning="loadingRevenue">
      <ve-line v-if="revenue.length > 0" :data="data" :settings="settings" width="100%"></ve-line>
      <div v-else style="text-align:center;padding:80px">
        No data
      </div>
    </a-spin>
  </a-card>
</template>

<script>
  import { mapGetters, mapActions } from "vuex";
  export default {
    data() {
      return {
        settings: {
          metrics: ["total"],
          dimension: ["label"]
        },
        type: "month",
        month: new Date().getMonth() + 1 + "",
        quarter: undefined,
        year: new Date().getFullYear() + ""
      };
    },
    computed: {
      ...mapGetters("dashboard", ["loadingRevenue", "revenue"]),
      data() {
        const rows = this.revenue;
        const data = {
          columns: ["label", "total"],
          rows: rows.length > 0 ? rows : []
        };
        return data;
      },
      disabledButton() {
        const { type, month, quarter, year } = this;
        if (!year) {
          return true;
        }
        if (type == "month") {
          if (!month) {
            return true;
          }
        }
        if (type == "quarter") {
          if (!quarter) {
            return true;
          }
        }
        return false;
      }
    },
    created() {
      this.fetchData();
    },
    methods: {
      ...mapActions("dashboard", ["fetchRevenue"]),
      onChangeType(e) {
        this.type = e.target.value;
        this.month = undefined;
        this.quarter = undefined;
      },
      fetchData(params = { type: this.type, month: this.month, year: this.year }) {
        this.fetchRevenue(params);
      },
      getRevenue() {
        const { type, month, quarter, year } = this;
        this.fetchRevenue({ type, month, quarter, year });
      }
    }
  };
</script>

<style scoped>
  .header {
    display: flex;
    flex-direction: row;
    align-items: center;
    justify-content: space-between;
  }
</style>
