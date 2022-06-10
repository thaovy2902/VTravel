<template>
  <a-card :loading="loadingRatioOrder" :bordered="false" :bodyStyle="{ padding: '16px' }" :headStyle="{ padding: '0 16px' }">
    <h3>{{ title }}</h3>
    <ve-ring :data="data" :settings="settings" height="380px" />
  </a-card>
</template>

<script>
  import { mapGetters, mapActions } from "vuex";
  export default {
    data() {
      return {
        title: "Tỉ lệ đặt tour",
        settings: {
          roseType: "radius",
          radius: [30, 100]
        }
      };
    },
    computed: {
      ...mapGetters("dashboard", ["totalOrderDomestic", "totalOrderForeign", "loadingRatioOrder"]),
      data() {
        const chartData = {
          columns: ["category", "times"],
          rows: [
            { category: "Trong nước", times: this.totalOrderDomestic },
            { category: "Ngoài nước", times: this.totalOrderForeign }
          ]
        };
        return chartData;
      }
    },
    created() {
      this.fetchRatioOrder();
    },
    methods: {
      ...mapActions("dashboard", ["fetchRatioOrder"])
    }
  };
</script>

<style></style>
