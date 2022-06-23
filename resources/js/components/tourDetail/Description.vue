<template>
  <a-card class="shadow-sm" :bordered="false" :bodyStyle="{ padding: '16px' }" style="margin-bottom:16px">
    <div class="container" slot="cover">
      <img :alt="tour.name" v-lazy="tour.image" />
      <div v-if="avgRating.count > 0" class="rating">
        <div>{{ avgRating.avg | rounded }}<a-icon class="icon" theme="filled" type="star" /></div>
        <a-divider type="vertical" />
        <div>{{ avgRating.count }} reviews</div>
      </div>
    </div>
    <a-row style="margin-bottom:8px">
      <a-col :xs="24" :sm="24" :md="8" :lg="8">
        <description-item icon="barcode" title="Tour ID" :content="tour.code" />
      </a-col>
      <a-col :xs="24" :sm="24" :md="8" :lg="8">
        <description-item icon="info-circle" title="Category" :content="tour.category_name" />
      </a-col>
      <a-col :xs="24" :sm="24" :md="8" :lg="8">
        <description-item icon="info-circle" title="Departure" :content="tour.depart | depart" />
      </a-col>
    </a-row>
    <a-row style="margin-bottom:8px">
      <a-col :xs="24" :sm="24" :md="8" :lg="8">
        <description-item icon="environment" title="Origin" :content="tour.from_place_name" />
      </a-col>
      <a-col :xs="24" :sm="24" :md="8" :lg="8">
        <description-item icon="environment" title="Destination" :content="tour.to_place_name" />
      </a-col>
      <a-col :xs="24" :sm="24" :md="8" :lg="8">
        <description-item icon="car" title="Transport" :content="tour.transport" />
      </a-col>
    </a-row>
    <a-row>
      <a-col :xs="24" :sm="24" :md="8" :lg="8">
        <description-item icon="history" title="Time" :content="`${tour.number_days} days`" />
      </a-col>
      <a-col :xs="24" :sm="24" :md="8" :lg="8">
        <description-item icon="team" title="Maximum Participants" :content="`${tour.number_persons} people`" />
      </a-col>
      <a-col :xs="24" :sm="24" :md="8" :lg="8">
        <a-tag v-if="tour.is_featured" color="blue">Top Attractions</a-tag>
      </a-col>
    </a-row>
  </a-card>
</template>

<script>
  import DescriptionItem from "@/components/description/DescriptionItem";
  import { mapState } from "vuex";

  export default {
    components: { DescriptionItem },
    props: {
      tour: {
        type: Object,
      },
    },
    computed: {
      ...mapState("tourDetail", ["avgRating"]),
    },
  };
</script>

<style lang="less" scoped>
  .container {
    position: relative;
    img {
      width: 100%;
      max-height: 360px;
      object-position: center;
      object-fit: cover;
      border-top-left-radius: 2px;
      border-top-right-radius: 2px;
    }
    .rating {
      position: absolute;
      top: 10px;
      left: -10px;
      display: flex;
      flex-direction: row;
      align-items: center;
      background: #fff;
      color: #000;
      padding: 8px;
      border-radius: 8px;
      box-shadow: 0 1px 6px 0 rgba(0, 0, 0, 0.2);
      div:first-child {
        font-size: 16px;
        font-weight: 500;
        color: #FF8F00;
        background: #FF8F0017;
        padding: 2px 4px;
        border-radius: 4px;
        .icon {
          font-size: 14px;
          color: #FF8F00;
          margin-left: 4px;
        }
      }
      div:last-child {
        font-size: 13px;
        font-weight: 400;
        font-style: italic;
      }
    }
  }
</style>
