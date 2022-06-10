<template>
  <div v-if="relatedTours.length > 0">
    <a-row :gutter="8">
      <a-col :xs="24" :sm="24" :md="16" :lg="16" class="mb-16">
        <span class="title">Tour liên quan</span>
        <a-divider type="vertical" />
        <router-link :to="{ name: 'tours' }">Xem thêm</router-link>
      </a-col>
    </a-row>
    <a-row :gutter="16">
      <tour-item v-for="tour in relatedTours" :key="tour.id" :data="tour" />
    </a-row>
  </div>
</template>

<script>
  import { mapGetters, mapActions } from "vuex";
  import TourItem from "@/components/tour/TourItem";
  export default {
    components: { TourItem },
    props: ["toPlaceCode", "currentTourId"],
    computed: {
      ...mapGetters("tourDetail", ["relatedTours"]),
    },
    beforeMount() {
      this.fetchData();
    },
    methods: {
      ...mapActions("tourDetail", ["fetchRelatedTours"]),
      fetchData() {
        const params = {
          currentTourId: this.currentTourId,
          toPlace: this.toPlaceCode,
        };
        this.fetchRelatedTours(params);
      },
    },
  };
</script>

<style scoped>
  .mb-16 {
    margin-bottom: 16px;
  }
  .title {
    font-size: 18px;
    font-weight: 500;
  }
</style>
