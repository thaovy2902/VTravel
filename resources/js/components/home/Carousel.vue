<template>
  <div>
    <a-carousel arrows autoplay effect="fade">
      <div slot="prevArrow" class="custom-slick-arrow" style="left: 10px; z-index: 1">
        <a-icon type="left" />
      </div>
      <div slot="nextArrow" class="custom-slick-arrow" style="right: 10px">
        <a-icon type="right" />
      </div>
      <div v-for="(slide, index) in slides" :key="index">
        <div class="slide-container">
          <img :src="slide.image" :alt="slide.title" />
          <router-link :to="slide.link">
            <div class="slide-title">
              {{ slide.title }}
            </div>
          </router-link>
        </div>
      </div>
    </a-carousel>
  </div>
</template>

<script>
  export default {
    props: {
      slides: {
        type: Array,
        default: [],
      },
    },
  };
</script>

<style lang="less" scoped>
  .ant-carousel {
    .slick-slide {
      text-align: center;
      overflow: hidden;
    }
    .custom-slick-arrow {
      width: 25px;
      height: 25px;
      font-size: 25px;
      color: #fff;
      opacity: 0.4;
      &:before {
        display: none;
      }
      &:hover {
        opacity: 0.7;
      }
    }
  }
  .slide-container {
    position: relative;
    img {
      height: 100%;
      width: 100%;
      border-radius: 2px;
      box-shadow: 0 0.125rem 0.25rem rgba(0, 0, 0, 0.075);
    }
    .slide-title {
      position: absolute;
      top: 88%;
      left: 50%;
      transform: translate(-50%, -50%);
      color: #fff;
      background: rgba(0, 0, 0, 0.45);
      padding: 4px 8px;
      border-radius: 8px;
      font-size: 16px;
      font-weight: lighter;
    }
  }
</style>
