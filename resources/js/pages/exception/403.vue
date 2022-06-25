<template>
  <exception-page type="403" />
</template>

<script>
  import ExceptionPage from "@/components/exception/ExceptionPage";
  export default {
    components: { ExceptionPage },
    metaInfo: {
      title: "Not Authorized",
    },
  };
</script>

<style scoped></style>
