<template>

  <!-- <a-row :gutter="16">
    <a-col :xs="0" :sm="0" :md="24" :lg="24" style="margin-bottom:16px">
      <top-rating :topRatings="topRatings" />
    </a-col>
  </a-row> -->
</template>

<script>
  import QuickSearch from "./QuickSearch";
  import TopRating from "./TopRating";
  export default {
    components: { QuickSearch, TopRating },
    props: {
      topRatings: {
        type: Array,
        default: [],
      },
    },
  };
</script>

<style scoped></style>
