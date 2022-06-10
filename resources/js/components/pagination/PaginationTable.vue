<template>
  <div class="pagination">
    <a-pagination
      showSizeChanger
      @showSizeChange="onShowSizeChange"
      @change="onChange"
      :current="current"
      :pageSize="pageSize"
      :pageSizeOptions="['5', '10', '15', '20']"
      :total="total"
      :showTotal="(total, range) => `${range[0]}-${range[1]} of ${total} items`"
    />
  </div>
</template>
<script>
  export default {
    props: {
      total: {
        type: Number,
        required: true
      },
      pageSize: {
        type: Number,
        required: true
      },
      current: {
        type: Number,
        required: true
      }
    },
    methods: {
      onShowSizeChange(current, pageSize) {
        this.$emit("onShowSizeChange", { current, pageSize });
      },
      onChange(page, pageSize) {
        this.$emit("onChange", { page, pageSize });
      }
    }
  };
</script>

<style lang="less" scoped>
  .pagination {
    text-align: center;
    margin-top: 24px;
  }
</style>
