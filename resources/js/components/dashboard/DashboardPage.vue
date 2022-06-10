<template>
  <div>
    <statistic />
    <revenue style="margin-top:16px" />
    <a-row :gutter="{ xs: 0, sm: 8, md: 16, lg: 16 }" style="margin-top:16px">
      <a-col :xs="24" :sm="24" :md="15" :lg="15">
        <ratio-order />
      </a-col>
      <a-col :xs="24" :sm="24" :md="9" :lg="9">
        <popular-tour />
      </a-col>
    </a-row>
    <new-order style="margin-top:16px" />
  </div>
</template>

<script>
  import Statistic from "./Statistic";
  import Revenue from "./Revenue";
  import RatioOrder from "./RatioOrder";
  import PopularTour from "./PopularTour";
  import NewOrder from "./NewOrder";
  export default {
    components: { Statistic, Revenue, RatioOrder, PopularTour, NewOrder }
  };
</script>

<style></style>
