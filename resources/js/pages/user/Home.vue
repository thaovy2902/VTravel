<template>
  <div>
    <loading-full-screen v-if="loadingSlide || loadingRating" />
    <section>
      <a-row style="margin: 0 -120px">
        <a-col :xs="24" :sm="24" :md="24" :lg="24">
          <banner/>
        </a-col>
      </a-row>
    </section>
     <section :style="{ margin: '24px 0' }">
      <a-row style="margin: 0 -120px">
        <a-col :xs="24" :sm="24" :md="24" :lg="24">
          <list-reason/>
        </a-col>
      </a-row>
    </section>
    <section :style="{ margin: '24px 0px 80px' }">
      <h2 class="large-title">Unmissable things to do</h2>
      <a-row :gutter="{ xs: 8, sm: 16, md: 16, lg: 16 }">
        <a-col :xs="0" :sm="0" :md="7" :lg="7">
          <home-util :topRatings="topRatings" />
        </a-col>
        <a-col :xs="24" :sm="24" :md="17" :lg="17">
          <carousel :slides="slides" />
        </a-col>
      </a-row>
    </section>
    <section :style="{ margin: '24px 0px' }">
      <a-row>
        <span class="tour-title">Newest tours</span>
        <list-tour-new :toursNew="toursNew" />
        <div style="text-align:center">
          <a-button @click="$router.push({ name: 'tours' })">More</a-button>
        </div>
      </a-row>

      <a-row>
        <span class="tour-title">Top attractions</span>
        <list-tour-featured :toursFeatured="toursFeatured" />
        <div style="text-align:center">
          <a-button @click="$router.push({ name: 'tours' })"></a-button>
        </div>
      </a-row>
    </section>
  </div>
</template>

<script>
  import HomeUtil from "@/components/home/HomeUtil";
  import Carousel from "@/components/home/Carousel";
  import Banner from "@/components/home/Banner";
  import ListReason from "@/components/home/ListReason";
  import ListTourNew from "@/components/home/ListTourNew";
  import ListTourFeatured from "@/components/home/ListTourFeatured";
  import { mapActions, mapGetters } from "vuex";
  export default {
    components: { HomeUtil, Carousel, ListTourNew, ListTourFeatured, Banner, ListReason },
    metaInfo: {
      title: "Home",
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
    display: block;
    margin-bottom: 24px;
    font-size: 24px;
    font-weight: 700;
    color: #111111;
  }
  h2.large-title {
    text-align: center;
    font-size: 28px;
    margin-bottom: 56px;
    color: #ff8f00;
  }
  button {
    padding: 0 40px;
    height: 40px;
    font-size: 18px;
    font-weight: 700;
    background-color: #ff8f00;
    border: #ff8f00;
  }
</style>
