<template>
  <div>
    <a-row :gutter="16">
      <tour-item v-for="tour in tours" :key="tour.id" :data="tour" />
    </a-row>
    <a-card :bordered="false" :bodyStyle="{ padding: '16px' }">
      <a-row type="flex" justify="center" align="middle">
        <a-col v-if="pagination.total > 0">
          <a-pagination
            size="small"
            showQuickJumper
            :current="pagination.current_page"
            :pageSize="pagination.per_page"
            :total="pagination.total"
            :showTotal="(total, range) => `${range[0]}-${range[1]} of ${total} items`"
            @change="onChange"
          />
        </a-col>
        <a-col v-else>Không tìm thấy kết quả</a-col>
      </a-row>
    </a-card>
  </div>
</template>

<script>
  import TourItem from "./TourItem";
  export default {
    components: { TourItem },
    props: {
      tours: {
        type: Array,
        default: [],
      },
      pagination: {
        type: Object,
      },
    },
    methods: {
      onChange(page) {
        this.$emit("changePage", page);
      },
    },
  };
</script>

<style></style>
