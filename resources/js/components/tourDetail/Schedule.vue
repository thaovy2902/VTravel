<template>
  <a-card class="shadow-sm" title="Itinerary" :bordered="false" :bodyStyle="{ padding: '16px' }" style="margin:16px 0">
    <div v-html="description" />
    <div v-if="tour.note">
      <a-divider>Note</a-divider>
      <div v-if="tour.note">
        {{ tour.note }}
      </div>
      <div v-else>
        <div style="text-align:center">No data</div>
      </div>
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
    computed: {
      description() {
        return md.parse(this.tour.description);
      },
    },
  };
</script>

<style></style>
