<template>
  <a-card :bordered="false" :bodyStyle="{ padding: '16px' }" class="shadow-sm">
    <div class="title">Tìm kiếm tour</div>
    <div style="margin-bottom:8px">
      <a-input placeholder="Tìm tour theo tên" v-model="query.q" allowClear />
    </div>

    <a-row :gutter="8" style="margin-bottom:16px">
      <a-col :span="12">
        <a-select placeholder="Loại tour" style="width:100%" @change="onChangeCategory">
          <a-select-option value="0">Tất cả</a-select-option>
          <a-select-option value="1">Trong nước</a-select-option>
          <a-select-option value="2">Ngoài nước</a-select-option>
        </a-select>
      </a-col>
      <a-col :span="12">
        <a-select placeholder="Chọn nơi đến" style="width:100%" v-model="toPlaceSelected" @change="onChangeDepart">
          <a-select-option v-for="city in cities" :key="city.code" :value="city.code">{{ city.name }}</a-select-option>
        </a-select>
      </a-col>
    </a-row>
    <a-button type="primary" icon="search" @click="search" block>Tìm kiếm</a-button>
  </a-card>
</template>

<script>
  export default {
    data() {
      return {
        query: {
          q: "",
          categoryId: "",
          toPlace: "",
        },
        toPlaceSelected: undefined,
      };
    },
    computed: {
      cities() {
        if (!this.query.categoryId) {
          return this.$root.cities;
        }
        if (this.query.categoryId == 1) {
          return this.$root.cities.filter((item) => item.code < 100);
        }
        if (this.query.categoryId == 2) {
          return this.$root.cities.filter((item) => item.code >= 100);
        }
      },
    },
    methods: {
      onChangeCategory(value) {
        this.query.categoryId = +value;
        this.toPlaceSelected = undefined;
      },
      onChangeDepart(value) {
        this.query.toPlace = value;
      },
      search() {
        const { query } = this;
        const sendQuery = {};
        if (query.q || query.categoryId || query.toPlace) {
          sendQuery.page = 1;
          if (query.q) sendQuery.q = query.q;
          if (query.categoryId) sendQuery.category = query.categoryId;
          if (query.toPlace) sendQuery.toPlace = query.toPlace;

          this.$router.push({ name: "tours", query: sendQuery });
        }
      },
    },
  };
</script>

<style scoped>
  .title {
    font-size: 16px;
    font-weight: 500;
    margin-bottom: 16px;
  }
</style>
