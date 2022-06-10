<template>
  <div>
    <loading-full-screen v-if="loadingSlide || loadingRating" />

    <section :style="{ margin: '24px 0' }">
      <a-row :gutter="{ xs: 8, sm: 16, md: 16, lg: 16 }">
        <a-col :xs="0" :sm="0" :md="7" :lg="7">
          <home-util :topRatings="topRatings" />
        </a-col>
        <a-col :xs="24" :sm="24" :md="17" :lg="17">
          <carousel :slides="slides" />
        </a-col>
      </a-row>
    </section>
    <section>
      <a-row>
        <a-divider>
          <span class="tour-title">Tour mới</span>
        </a-divider>
        <list-tour-new :toursNew="toursNew" />
        <div style="margin-top:24px;text-align:center">
          <a-button @click="$router.push({ name: 'tours' })">Xem thêm</a-button>
        </div>
      </a-row>

      <a-row>
        <a-divider>
          <span class="tour-title">Tour nổi bật</span>
        </a-divider>
        <list-tour-featured :toursFeatured="toursFeatured" />
        <div style="margin-top:24px;text-align:center">
          <a-button @click="$router.push({ name: 'tours' })">Xem thêm</a-button>
        </div>
      </a-row>
    </section>
  </div>
</template>

<script>
  import HomeUtil from "@/components/home/HomeUtil";
  import Carousel from "@/components/home/Carousel";
  import ListTourNew from "@/components/home/ListTourNew";
  import ListTourFeatured from "@/components/home/ListTourFeatured";
  import { mapActions, mapGetters } from "vuex";
  export default {
    components: { HomeUtil, Carousel, ListTourNew, ListTourFeatured },
    metaInfo: {
      title: "Trang chủ",
    },
    computed: {
      ...mapGetters("home", [
        "slides",
        "topRatings",
        "toursNew",
        "toursFeatured",
        "loadingSlide",
        "loadingRating",
        "loadingToursNew",
        "loadingToursFeatured",
      ]),
    },
    watch: {
      $route: "fetch",
    },
    created() {
      this.fetch();
    },
    methods: {
      ...mapActions("home", ["fetchSlides", "fetchTopRating", "fetchToursNew", "fetchToursFeatured"]),
      fetch() {
        Promise.all([this.fetchSlides(), this.fetchTopRating(), this.fetchToursFeatured(), this.fetchToursNew()]);
      },
    },
  };
</script>

<style>
  span.tour-title {
    font-size: 24px;
    font-weight: 700;
    text-transform: uppercase;
  }
</style>
