<template>
  <a-card :title="title" :bordered="false">
    <div slot="extra">
      <slot></slot>
    </div>
    <a-table :bordered="bordered" :columns="columns" :loading="loading" :rowKey="rowKey" :dataSource="dataSource" :pagination="pagination">
    </a-table>
  </a-card>
</template>

<script>
  export default {
    props: ["title", "bordered", "loading", "columns", "dataSource", "rowKey", "pagination"]
  };
</script>

<style></style>
