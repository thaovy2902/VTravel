<template>
  <a-card :title="title" :bordered="false" :bodyStyle="{ padding: '16px' }" :headStyle="{ padding: '0 16px' }">
    <div slot="extra">
      <a-button v-if="addButton" type="primary" icon="plus" @click="open">
        Thêm mới
      </a-button>
      <a-button class="reload" icon="reload" @click="reset">
        Tải lại
      </a-button>
      <a-input-search v-if="!noSearch" class="search" :placeholder="placeholder" allowClear @search="search" />
    </div>
    <slot></slot>
  </a-card>
</template>

<script>
  export default {
    props: {
      title: {
        type: String,
        required: true,
      },
      addButton: {
        type: Boolean,
        default: false,
      },
      noSearch: {
        type: Boolean,
        default: false,
      },
      placeholder: {
        type: String,
        default() {
          return "Tìm kiếm...";
        },
      },
    },
    methods: {
      open() {
        this.$emit("open");
      },
      reset() {
        this.$emit("reset");
      },
      search(value) {
        this.$emit("search", value);
      },
    },
  };
</script>

<style scoped>
  .reload {
    margin-left: 8px;
  }
  .search {
    width: 250px;
    margin-left: 8px;
  }
</style>
