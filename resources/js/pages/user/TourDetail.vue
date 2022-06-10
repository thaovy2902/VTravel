<template>
  <div :style="{ marginTop: '24px' }">
    <loading-full-screen v-if="loading" />
    <div v-else>
      <div style="margin-bottom: 16px;">
        <a-breadcrumb>
          <a-breadcrumb-item>
            <router-link :to="{ name: 'home' }">Trang chủ</router-link>
          </a-breadcrumb-item>
          <a-breadcrumb-item>
            <router-link :to="{ name: 'tours' }">Tour</router-link>
          </a-breadcrumb-item>
          <a-breadcrumb-item>
            {{ tour.name }}
          </a-breadcrumb-item>
        </a-breadcrumb>
      </div>

      <a-row :gutter="{ xs: 8, sm: 8, md: 16, lg: 16 }">
        <a-col>
          <h1 style="font-weight: 500; color: #0056bb;">{{ tour.name }}</h1>
        </a-col>
        <a-col :xs="24" :sm="24" :md="16" :lg="16">
          <!-- description -->
          <description :tour="tour" />
          <!-- end description -->

          <!-- schedule -->
          <gallery :tour="tour" />
          <!-- end schedule -->

          <!-- schedule -->
          <schedule :tour="tour" />
          <!-- end schedule -->

          <!-- rating -->
          <rating :tour="tour" />
          <!-- end rating -->
        </a-col>
        <a-col :xs="24" :sm="24" :md="8" :lg="8">
          <!-- price tour -->
          <price-tour :tour="tour" />
          <!-- end price tour -->

          <!-- order -->
          <a-affix :offsetTop="80" :target="targetAffix">
            <order :tour="tour" />
          </a-affix>
          <!-- end order -->
        </a-col>

        <a-col :xs="24" :sm="24" :md="24" :lg="24">
          <!-- related tour -->
          <related-tour :to-place-code="tour.to_place_code" :current-tour-id="tour.id" />
          <!-- end related tour -->
        </a-col>
      </a-row>
    </div>
  </div>
</template>

<script>
  import { mapGetters, mapActions } from "vuex";
  import Description from "@/components/tourDetail/Description";
  import Gallery from "@/components/tourDetail/Gallery";
  import Schedule from "@/components/tourDetail/Schedule";
  import Rating from "@/components/tourDetail/Rating";
  import PriceTour from "@/components/tourDetail/PriceTour";
  import Order from "@/components/tourDetail/Order";
  import RelatedTour from "@/components/tourDetail/RelatedTour";
  export default {
    components: { Description, Gallery, Schedule, Rating, PriceTour, Order, RelatedTour },
    metaInfo() {
      return {
        title: this.tour.name,
        meta: [{ vmid: "description", name: "description", content: this.tour.name }],
      };
    },
    computed: {
      ...mapGetters("tourDetail", ["tour", "loading"]),
    },
    watch: {
      $route: "getTour",
    },
    created() {
      this.getTour();
    },
    methods: {
      ...mapActions("tourDetail", ["getTour"]),
      targetAffix() {
        return document.getElementById("app");
      },
    },
  };
</script>

<style></style>
