<template>
  <a-col :style="{ marginTop: '16px' }">
    <a-row :gutter="16" class="row-featured">
      <tour-item v-for="tour in toursFeatured" :key="tour.id" :data="tour" />
    </a-row>
  </a-col>
</template>

<script>
  import TourItem from "@/components/tour/TourItem";
  export default {
    components: { TourItem },
    props: {
      toursFeatured: {
        type: Array,
        default: []
      }
    }
  };
</script>

<style>
  .row-featured {
    margin: 0 -40px !important;
  }
</style>
