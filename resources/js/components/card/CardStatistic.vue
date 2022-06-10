<template>
  <a-card :loading="loading" :bordered="false" :bodyStyle="{ padding: '16px' }">
    <div class="chart-card-header">
      <div class="meta">
        <span class="chart-card-title">{{ title }}</span>
        <span class="chart-card-action">
          <slot name="action"></slot>
        </span>
      </div>
      <div class="total">
        <slot name="total"></slot>
      </div>
    </div>
    <div class="chart-card-footer">
      <slot name="footer"></slot>
    </div>
  </a-card>
</template>

<script>
  export default {
    props: {
      title: {
        type: String
      },
      loading: {
        type: Boolean,
        default: false
      }
    }
  };
</script>

<style scoped>
  .chart-card-header {
    position: relative;
    overflow: hidden;
    width: 100%;
  }
  .chart-card-header .meta {
    position: relative;
    overflow: hidden;
    width: 100%;
    color: rgba(0, 0, 0, 0.45);
    font-size: 14px;
    line-height: 22px;
  }
  .chart-card-action {
    cursor: pointer;
    position: absolute;
    top: 0;
    right: 0;
  }
  .total {
    overflow: hidden;
    text-overflow: ellipsis;
    word-break: break-all;
    white-space: nowrap;
    color: #000;
    margin-top: 4px;
    margin-bottom: 0;
    font-size: 26px;
    line-height: 38px;
    height: 38px;
  }
  .chart-card-footer {
    border-top: 1px solid #e8e8e8;
    padding-top: 9px;
    margin-top: 8px;
  }
</style>
