<template>
  <a-card :bordered="false" :bodyStyle="{ padding: '16px' }" style="overflow-y: scroll; height: 450px; padding-right: 40px;" class="shadow-sm">
    <div class="title">Recent Feedback</div>
    <div>
      <div v-for="(rating, index) in topRatings" :key="index" class="rating">
        <div class="rating-header">
          <div>
            <a-avatar v-if="rating.avatar" :size="32" :src="rating.avatar" style="margin-right:4px" />
            <a-avatar v-else :size="32" icon="user" style="margin-right:4px" />
            <span style="font-weight:500">{{ rating.author }}</span>
          </div>
          <div><a-icon v-for="(rating, index) in rating.scores" :key="index" theme="filled" type="star" style="font-size:16px; color:#fadb14" /></div>
        </div>
        <div class="rating-content" :title="rating.content">{{ rating.content }}</div>
      </div>
    </div>
  </a-card>
</template>

<script>
  export default {
    props: {
      topRatings: {
        type: Array,
        default: [],
      },
    },
  };
</script>

<style scoped>
  .title {
    font-size: 16px;
    font-weight: 500;
    margin-bottom: 16px;
  }
  .rating-header {
    display: flex;
    flex-direction: row;
    align-items: center;
    justify-content: space-between;
    margin-bottom: 6px;
  }
  .rating-content {
    display: -webkit-box;
    -webkit-box-orient: vertical;
    -webkit-line-clamp: 4;
    overflow: hidden;
    text-overflow: ellipsis;
  }
  .rating {
    margin-bottom: 24px;
  }
</style>
