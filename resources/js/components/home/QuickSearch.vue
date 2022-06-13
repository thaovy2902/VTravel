<template>
  <a-card :bordered="false" :bodyStyle="{ }" class="shadow-sm" id="quick-search">
    <div style="margin-bottom: 16px">
      <a-input class="search-input" placeholder="What do you want to do?" v-model="query.q" allowClear />
    </div>
    <a-row :gutter="8" style="margin-bottom:16px">
      <a-col :span="12">
        <a-select class="search-input" placeholder="Choose destination" style="width:100%" v-model="toPlaceSelected" @change="onChangeDepart">
          <a-select-option  v-for="city in cities" :key="city.code" :value="city.code">{{ city.name }}</a-select-option>
        </a-select>
      </a-col>
      <a-col :span="12">
        <a-button class="search-button" type="primary" icon="search" @click="search" block>Search</a-button>
      </a-col>
    </a-row>
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
    // created() {
    //   this.initForm();
    // },
    methods: {
      // initForm() {
      //   this.form.getFieldDecorator("category_id", { initialValue: undefined });
      // },
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

<style>
  #quick-search {
    position: absolute;
    width: 50%;
    background: transparent;
    padding: 0 120px;
  }
  #quick-search .ant-card-body {
    padding: 0;
  }
  .title {
    font-size: 16px;
    font-weight: 500;
    margin-bottom: 16px;
  }
  .search-input {
    height: 40px;
  }
  .search-input input {
    font-size: 18px;
  }
  .search-input .ant-select-selection--single {
    height: 40px;
    font-size: 18px;
  }
  .search-input .ant-select-selection--single .ant-select-selection__rendered {
    height: 100%;
  }
  .search-button {
    height: 40px;
    font-size: 18px;
  }
</style>
