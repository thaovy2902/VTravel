<template>
  <div style="max-width: 500px; margin: 40px auto">
    <result :is-success="true" :title="title" :description="description">
      <div slot="action">
        <a-button type="primary" @click="$router.push({ name: 'home' })">Home</a-button>
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
        title: "Order Successful",
        description: `Thank you so much for your order. You'll receive an email confirmation shortly.
          Please contact us if you have any questions.`
      };
    }
  };
</script>

<style></style>
