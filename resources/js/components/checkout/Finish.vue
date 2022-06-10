<template>
  <div style="max-width: 500px; margin: 40px auto">
    <result :is-success="true" :title="title" :description="description">
      <div slot="action">
        <a-button type="primary" @click="$router.push({ name: 'home' })">Về trang chủ</a-button>
      </div>
    </result>
  </div>
</template>

<script>
  import Result from "@/components/result/Result";
  export default {
    components: { Result },
    data() {
      return {
        title: "Đặt tour thành công",
        description: `Cảm ơn quý khách đã sử dụng dịch vụ của chúng tôi. 
          Mọi thắc mắc xin vui lòng liên hệ info@travelbot.com hoặc +84 0101 2345.
          Xin cảm ơn!`
      };
    }
  };
</script>

<style></style>
