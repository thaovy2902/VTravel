<template>
  <exception-page type="500" />
</template>

<script>
  import ExceptionPage from "@/components/exception/ExceptionPage";
  export default {
    components: { ExceptionPage },
    metaInfo: {
      title: "Lỗi server",
    },
  };
</script>

<style scoped></style>
