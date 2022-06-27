<template>
  <a-card :bordered="false" :bodyStyle="{ padding: '16px'}">
    <div class="card-title-custom"><a-icon type="filter" style="margin-right:6px" />SEARCH FILTER</div>

    <div class="form-group">
      <span>By Category</span>
      <div class="form-input">
        <a-radio-group buttonStyle="solid" @change="onChangeCategory">
          <a-radio :style="radioStyle" value="0">All</a-radio>
          <a-radio :style="radioStyle" v-for="cate in categories" :key="cate.id" :value="cate.id">
            {{ cate.name }}
          </a-radio>
        </a-radio-group>
      </div>
    </div>
    <a-divider />

    <div class="form-group">
      <span>By Destination</span>
      <div class="form-input">
        <a-select placeholder="Destination" style="width: 100%" :value="$route.query.toPlace || undefined" @change="onChangeToPlace">
          <a-select-option v-for="city in cities" :key="city.code" :value="city.code">{{ city.name }}</a-select-option>
        </a-select>
      </div>
    </div>
    <a-divider />

    <div class="form-group">
      <span>Date</span>
      <div class="form-input">
        <a-select placeholder="Departure" :value="$route.query.depart || undefined" style="width: 100%" @change="onChangeDepart">
          <a-select-option value="daily">Daily</a-select-option>
          <a-select-option value="contact">Contact</a-select-option>
        </a-select>
      </div>
    </div>
    <a-divider />

    <!-- <div class="form-group">
      <span>Option</span>
      <div class="form-input">
        <a-checkbox ref="featured" :checked="!$route.query.featured || false" @change="onChangeFeatured">Is attraction</a-checkbox>
      </div>
    </div>
    <a-divider /> -->

    <div class="form-group">
      <span>Price range</span>
      <div class="form-input">
        <a-input-group>
          <a-row :gutter="8">
            <a-col :span="12">
              <a-input-number
                step="10000"
                :min="0"
                :formatter="(value) => `${value}`.replace(/\B(?=(\d{3})+(?!\d))/g, '.')"
                :parser="(value) => value.replace(/\$\s?|(\.*)/g, '')"
                @change="(value) => (minPrice = value)"
                style="width:100%"
              />
            </a-col>
            <a-col :span="12">
              <a-input-number
                step="10000"
                :min="minPrice"
                :formatter="(value) => `${value}`.replace(/\B(?=(\d{3})+(?!\d))/g, '.')"
                :parser="(value) => value.replace(/\$\s?|(\.*)/g, '')"
                @change="(value) => (maxPrice = value)"
                style="width:100%"
              />
            </a-col>
          </a-row>
        </a-input-group>

        <a-button style="margin-top: 12px" type="primary" block @click="onClickChangePrice" :disabled="minPrice === 0 || maxPrice === 0">
          Apply
        </a-button>
      </div>
    </div>
    <a-divider />

    <!-- <div>
      <a-button block @click="clearFilter" :disabled="$route.fullPath === '/tours'">Clear all</a-button>
    </div> -->
  </a-card>
</template>

<script>
  import { mapActions, mapGetters } from "vuex";
  export default {
    data() {
      return {
        radioStyle: {
          display: "block",
          height: "30px",
          lineHeight: "30px",
        },
        categoryId: "",
        minPrice: 0,
        maxPrice: 0,
      };
    },
    computed: {
      ...mapGetters("tour", ["categories"]),
      cities() {
        if (!this.categoryId) {
          return this.$root.cities;
        }
        if (this.categoryId == 1) {
          return this.$root.cities.filter((item) => item.code < 100);
        }
        if (this.categoryId == 2) {
          return this.$root.cities.filter((item) => item.code >= 100);
        }
      },
    },
    created() {
      this.fetchCategories();
    },
    methods: {
      ...mapActions("tour", ["fetchCategories"]),
      onChangeCategory(e) {
        const categoryId = +e.target.value;
        this.categoryId = categoryId;
        this.$emit("changeCategory", categoryId);
      },
      onChangeDepart(value) {
        this.$emit("changeDepart", value);
      },
      onChangeToPlace(value) {
        this.$emit("changeToPlace", value);
      },
      onChangeFeatured(e) {
        this.$emit("changeFeatured", e.target.checked);
      },
      onClickChangePrice() {
        this.$emit("changePrice", this.minPrice, this.maxPrice);
      },
      clearFilter() {
        const fullPath = this.$route.fullPath;
        this.$emit("changeCategory", 0);
        if (fullPath !== "/tours") {
          this.$emit("clearFilter");
        }
      },
    },
  };
</script>

<style lang="less" scoped>
  .card-title-custom {
    font-weight: 700;
    font-size: 16px;
    text-transform: uppercase;
    margin-bottom: 24px;
    color: #111111;
  }
  .form-group {
    margin-bottom: 24px;
    span {
      font-size: 14px;
      text-transform: uppercase;
      font-weight: 500;
      color: rgb(37, 149, 255);
    }
    .form-input {
      margin-top: 8px;
    }
  }
  .ant-divider-horizontal {
    margin: 16px 0;
  }
</style>
