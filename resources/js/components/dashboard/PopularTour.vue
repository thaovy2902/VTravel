<template>
  <a-card :loading="loadingPopularTour" :bordered="false" :bodyStyle="{ padding: '16px' }" :headStyle="{ padding: '0 16px' }">
    <h3 style="margin-bottom:12px">{{ title }}</h3>
    <a-row :key="index" v-for="(item, index) in popularTour" type="flex" align="middle" class="mb-12">
      <a-col :span="2">
        <div :class="['sort', index < 3 ? 'active' : null]">{{ ++index }}</div>
      </a-col>
      <a-col :span="19">
        <div class="name">{{ item.tour_name }}</div>
      </a-col>
      <a-col :span="3">
        <div class="times">{{ item.times_order }} lần</div>
      </a-col>
    </a-row>
  </a-card>
</template>

<script>
  import { mapActions, mapGetters } from "vuex";
  export default {
    data() {
      return {
        title: "Tour phổ biến"
      };
    },
    computed: {
      ...mapGetters("dashboard", ["loadingPopularTour", "popularTour"])
    },
    created() {
      this.fetchPopularTour();
    },
    methods: {
      ...mapActions("dashboard", ["fetchPopularTour"])
    }
  };
</script>

<style lang="less" scoped>
  .mb-12 {
    margin-bottom: 12px;
  }
  .sort {
    background-color: #f5f5f5;
    border-radius: 20px;
    display: inline-block;
    font-size: 12px;
    font-weight: 600;
    height: 20px;
    line-height: 20px;
    width: 20px;
    text-align: center;
    text-overflow: ellipsis;
  }
  .active {
    background-color: #314659;
    color: #fff;
  }
  .name {
    display: -webkit-box;
    -webkit-box-orient: vertical;
    -webkit-line-clamp: 1;
    overflow: hidden;
    text-overflow: ellipsis;
  }
  .times {
    text-align: center;
  }
</style>
