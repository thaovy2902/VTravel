<template>
  <a-col :style="{ marginTop: '16px' }">
    <a-row :gutter="16">
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

<style></style>
