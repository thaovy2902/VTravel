<template>
  <a-card :bordered="false" :bodyStyle="{ padding: '16px' }" :headStyle="{ padding: '0 16px' }">
    <div class="header">
      <h3>Doanh thu</h3>
      <div>
        <a-select v-if="type === 'month'" v-model="month" placeholder="Chọn tháng" style="width:150px;margin-right:8px">
          <a-select-option value="1">Tháng 1</a-select-option>
          <a-select-option value="2">Tháng 2</a-select-option>
          <a-select-option value="3">Tháng 3</a-select-option>
          <a-select-option value="4">Tháng 4</a-select-option>
          <a-select-option value="5">Tháng 5</a-select-option>
          <a-select-option value="6">Tháng 6</a-select-option>
          <a-select-option value="7">Tháng 7</a-select-option>
          <a-select-option value="8">Tháng 8</a-select-option>
          <a-select-option value="9">Tháng 9</a-select-option>
          <a-select-option value="10">Tháng 10</a-select-option>
          <a-select-option value="11">Tháng 11</a-select-option>
          <a-select-option value="12">Tháng 12</a-select-option>
        </a-select>
        <a-select v-if="type === 'quarter'" v-model="quarter" placeholder="Chọn quý" style="width:150px;margin-right:8px">
          <a-select-option value="1">Quý 1</a-select-option>
          <a-select-option value="2">Quý 2</a-select-option>
          <a-select-option value="3">Quý 3</a-select-option>
          <a-select-option value="4">Quý 4</a-select-option>
        </a-select>
        <a-select placeholder="Chọn năm" v-model="year" style="width:150px;margin-right:8px">
          <a-select-option value="2019">2019</a-select-option>
          <a-select-option value="2020">2020</a-select-option>
        </a-select>

        <a-button type="primary" :disabled="disabledButton" @click="getRevenue">Xem</a-button>

        <a-radio-group :defaultValue="type" @change="onChangeType" style="margin-left:8px">
          <a-radio-button value="month">Tháng</a-radio-button>
          <a-radio-button value="quarter">Quý</a-radio-button>
          <a-radio-button value="year">Năm</a-radio-button>
        </a-radio-group>
      </div>
    </div>
    <a-spin :spinning="loadingRevenue">
      <ve-line v-if="revenue.length > 0" :data="data" :settings="settings" width="100%"></ve-line>
      <div v-else style="text-align:center;padding:50px">
        Không có dữ liệu
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
