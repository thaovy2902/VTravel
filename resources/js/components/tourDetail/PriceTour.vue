<template>
  <a-card class="shadow-sm" title="Tour Price" :bordered="false" :bodyStyle="{ padding: '16px' }" :style="{ marginBottom: '16px' }">
    <div>
      Adults: <b>{{ tour.price_default | currencyVN }}</b>
    </div>
    <div :style="{ margin: '10px 0' }">
      Children (2-12): <b>{{ tour.price_children | currencyVN }}</b> 
    </div>
    <div>
      Infants (0-2): <b>{{ tour.price_baby | currencyVN }}</b>
    </div>
  </a-card>
</template>

<script>
  export default {
    props: {
      tour: {
        type: Object,
      },
    },
  };
</script>

<style></style>
