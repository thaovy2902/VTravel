<template>
  <a-card class="shadow-sm" :bordered="false" :bodyStyle="{ padding: '16px' }" style="margin:12px 0">
    <div v-if="tour.gallery != null">
      <a-carousel arrows dotsClass="slick-dots slick-thumb" autoplay effect="fade" style="paddingBottom:55px">
        <a slot="customPaging" slot-scope="props">
          <img :src="getImgUrl(props.i)" />
        </a>
        <div slot="prevArrow" class="custom-slick-arrow" style="left: 10px;zIndex: 1">
          <a-icon type="left-circle" />
        </div>
        <div slot="nextArrow" class="custom-slick-arrow" style="right: 10px">
          <a-icon type="right-circle" />
        </div>
        <div v-for="(image, index) in tour.gallery" :key="index">
          <img :src="image" :alt="`${tour.name}-${index}`" />
        </div>
      </a-carousel>
    </div>
    <div v-else>
      <div style="text-align:center">Không có hình nào</div>
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
    methods: {
      getImgUrl(i) {
        return this.tour.gallery[i];
      },
    },
  };
</script>

<style scoped>
  .ant-carousel >>> .slick-dots {
    height: auto;
  }
  .ant-carousel >>> .slick-slide img {
    width: 100%;
  }
  .ant-carousel >>> .slick-thumb {
    bottom: -55px;
  }
  .ant-carousel >>> .slick-thumb li {
    width: 70px;
    height: 55px;
  }
  .ant-carousel >>> .slick-thumb li img {
    width: 100%;
    height: 100%;
    opacity: 0.6;
  }
  .ant-carousel >>> .slick-thumb li.slick-active img {
    opacity: 1;
  }

  .ant-carousel >>> .slick-slide {
    text-align: center;
    overflow: hidden;
  }
  .ant-carousel >>> .custom-slick-arrow {
    width: 25px;
    height: 25px;
    font-size: 25px;
    color: #fff;
    opacity: 0.4;
  }
  .ant-carousel >>> .custom-slick-arrow:before {
    display: none;
  }
  .ant-carousel >>> .custom-slick-arrow:hover {
    opacity: 0.7;
  }
</style>
