<template>
  <div id="banner">
      <h2 class="banner-title">Adventure Awaits, <br> Go Find It.</h2>
      <quick-search />
  </div>
</template>

<script>
  import QuickSearch from "./QuickSearch";
  export default {
    components: { QuickSearch },
  };
</script>

<style>
  #banner {
    position: relative;
    height: 500px;
    width: 100%;
    font-size: 48px;
    background-image: url('https://res.klook.com/image/upload/fl_lossy.progressive,q_85/c_fill,,w_1920,/v1607408067/ued/ttd/banner/jpg/ttd_veritcal_page_banner_tours.jpg');
    background-position: center;
  }
  #banner .banner-title {
    padding: 40px 120px;
    color: #ffffff;
    margin-bottom: 0;
  }
</style>
