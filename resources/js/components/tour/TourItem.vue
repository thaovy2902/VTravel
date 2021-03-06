<template>
  <a-col class="tour-item" :xs="col.xs" :sm="col.sm" :md="col.md" :lg="col.lg">
    <router-link :to="{ name: 'tours.show', params: { slug: data.slug } }">
      <a-card :bordered="false" :bodyStyle="{ padding: '16px' }" class="tour-item-card" style="box-shadow: 0 2px 8px rgb(0 0 0 / 9%); border-radius: 20px; position: relative;" hoverable>
        <div style="overflow: hidden;">
          <img class="img-tour" :alt="data.name" v-lazy="data.image" slot="cover" />
        </div>
        <a-card-meta :title="data.name">
          <template slot="description">
            <div class="description">{{ data.from_place_name }} <a-icon type="arrow-right" /> {{ data.to_place_name }}</div>
          </template>
        </a-card-meta>
        <div class="content">
          <div class="content-rating">
            <a-rate :defaultValue="ratingRounded" disabled allowHalf />
            | {{ data.count_rating }} reviews
          </div>
          <span class="content-price">{{ data.price_default | currencyVN }}</span>
        </div>
      </a-card>
    </router-link>
  </a-col>
</template>

<script>
  export default {
    props: {
      data: {
        type: Object,
        required: true,
      },
      col: {
        type: Object,
        default() {
          return {
            xs: 24,
            sm: 12,
            md: 8,
            lg: 8,
          };
        },
      },
    },
    computed: {
      ratingRounded() {
        return Math.round(this.data.avg_rating * 100) / 100;
      },
    },
  };
</script>

<style lang="less" scoped>
  .tour-item {
    padding: 0 40px !important;
    margin-bottom: 40px;
  }
  .tour-item-card {
    &:hover {
      img {
        transform: scale(1.1);
      }
    }
  }
  img.img-tour {
    width: 100%;
    height: 280px;
    border-radius: 10px;
    margin-bottom: 20px;
    object-fit: cover;
    object-position: center;
    border-top-left-radius: 2px;
    border-top-right-radius: 2px;
    transition: 0.5s ease;
  }
  .content {
    margin-top: 8px;
    margin-bottom: -4px;
    .content-rating {
      font-size: 13px;
      font-weight: 600;
      margin-bottom: 24px;
    }
    .content-price {
      color: #ff0000;
      background: #ffffff;
      padding: 4px 8px;
      border-radius: 4px;
      font-size: 18px;
      font-weight: 700;
      position: absolute;
      right: 20px;
      top: 20px;
    }
  }
  .mb-16 {
    margin-bottom: 16px;
  }
  .description {
    display: -webkit-box;
    -webkit-box-orient: vertical;
    -webkit-line-clamp: 1;
    overflow: hidden;
    text-overflow: ellipsis;
    margin-bottom: 12px;
  }
</style>
