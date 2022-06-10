<template>
  <a-card :bordered="false" :bodyStyle="{ padding: '16px' }">
    <div class="card-title-custom"><a-icon type="filter" style="margin-right:6px" />Bộ lọc tìm kiếm</div>

    <div class="form-group">
      <span>Loại tour</span>
      <div class="form-input">
        <a-radio-group :value="$route.query.category ? $route.query.category + '' : '0'" buttonStyle="solid" @change="onChangeCategory">
          <a-radio :style="radioStyle" value="0">Tất cả</a-radio>
          <a-radio :style="radioStyle" value="1">Trong nước</a-radio>
          <a-radio :style="radioStyle" value="2">Ngoài nước</a-radio>
        </a-radio-group>
      </div>
    </div>
    <a-divider />

    <div class="form-group">
      <span>Nơi đến</span>
      <div class="form-input">
        <a-select placeholder="Nơi đến" style="width: 100%" :value="$route.query.toPlace || undefined" @change="onChangeToPlace">
          <a-select-option v-for="city in cities" :key="city.code" :value="city.code">{{ city.name }}</a-select-option>
        </a-select>
      </div>
    </div>
    <a-divider />

    <div class="form-group">
      <span>Khởi hành</span>
      <div class="form-input">
        <a-select placeholder="Khởi hành" :value="$route.query.depart || undefined" style="width: 100%" @change="onChangeDepart">
          <a-select-option value="daily">Hằng ngày</a-select-option>
          <a-select-option value="contact">Liên hệ</a-select-option>
        </a-select>
      </div>
    </div>
    <a-divider />

    <div class="form-group">
      <span>Nổi bật</span>
      <div class="form-input">
        <a-checkbox ref="featured" :checked="!!$route.query.featured || false" @change="onChangeFeatured">Nổi bật</a-checkbox>
      </div>
    </div>
    <a-divider />

    <div class="form-group">
      <span>Khoảng giá</span>
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
          Áp dụng
        </a-button>
      </div>
    </div>
    <a-divider />

    <div>
      <a-button block @click="clearFilter" :disabled="$route.fullPath === '/tours'">Xóa lọc</a-button>
    </div>
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
    methods: {
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
    font-size: 14px;
    text-transform: uppercase;
    margin-bottom: 24px;
  }
  .form-group {
    margin-bottom: 24px;
    span {
      font-size: 14px;
      text-transform: uppercase;
      font-weight: 500;
    }
    .form-input {
      margin-top: 8px;
    }
  }
  .ant-divider-horizontal {
    margin: 16px 0;
  }
</style>
