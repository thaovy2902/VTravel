<template>
  <a-card class="shadow-sm" title="Giá tour" :bordered="false" :bodyStyle="{ padding: '16px' }" :style="{ marginBottom: '16px' }">
    <div>
      Người lớn: <b>{{ tour.price_default | currencyVN }}</b>
    </div>
    <div :style="{ margin: '10px 0' }">
      Trẻ em: <b>{{ tour.price_children | currencyVN }}</b> (từ 2 tuổi đến dưới 12 tuổi)
    </div>
    <div>
      Em bé: <b>{{ tour.price_baby | currencyVN }}</b> (dưới 2 tuổi)
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
