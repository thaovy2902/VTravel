<template>
  <a-col class="mb-16" :xs="col.xs" :sm="col.sm" :md="col.md" :lg="col.lg">
    <router-link :to="{ name: 'tours.show', params: { slug: data.slug } }">
      <a-card :bordered="false" :bodyStyle="{ padding: '16px' }" hoverable>
        <img class="img-tour" :alt="data.name" v-lazy="data.image" slot="cover" />
        <a-card-meta :title="data.name">
          <template slot="description">
            <div class="description">{{ data.from_place_name }} <a-icon type="arrow-right" /> {{ data.to_place_name }}</div>
          </template>
        </a-card-meta>
        <div class="content">
          <div class="content-rating">
            <a-rate :defaultValue="ratingRounded" disabled allowHalf />
            | {{ data.count_rating }} đánh giá
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
  img.img-tour {
    width: 100%;
    height: 280px;
    object-fit: cover;
    object-position: center;
    border-top-left-radius: 2px;
    border-top-right-radius: 2px;
    transition: 0.5s ease;
    &:hover {
      opacity: 0.55;
    }
  }
  .content {
    margin-top: 8px;
    margin-bottom: -4px;
    .content-rating {
      font-size: 13px;
      font-weight: 600;
    }
    .content-price {
      color: #FF8F00;
      background: #FF8F0017;
      padding: 2px 4px;
      border-radius: 4px;
      font-size: 16px;
      font-weight: 700;
      float: right;
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
  }
</style>
