<template>
  <card-table :title="title" @reset="reset" :noSearch="true">
    <a-table
      :columns="columns"
      :loading="loading"
      :rowKey="record => record.id"
      :dataSource="ratings"
      :pagination="false"
      @change="onTableChange"
    >
      <strong slot="no" slot-scope="text, record, index">{{ ++index }}</strong>
      <template slot="scores" slot-scope="record">
        <span>
          {{ record }}
          <a-icon type="star" theme="filled" :style="{ color: '#ffcb2b' }" />
        </span>
      </template>
      <template slot="active" slot-scope="record">
        <a-switch
          size="small"
          :key="record.id"
          :name="`r__${record.id}`"
          :checked="record.is_active"
          :defaultChecked="record.is_active"
          @click="onClickActive"
        >
        </a-switch>
      </template>
      <template slot="action" slot-scope="record">
        <a-popconfirm v-if="ratings.length" title="Bạn có chắc chắn?" @confirm="onDelete(record.id)">
          <a-button type="dashed" size="small">
            <a-icon type="delete"></a-icon>
          </a-button>
        </a-popconfirm>
      </template>
    </a-table>
    <pagination-table
      :total="pagination.total"
      :pageSize="pagination.perPage"
      :current="pagination.current"
      @onShowSizeChange="onShowSizeChange"
      @onChange="onChange"
    />
  </card-table>
</template>

<script>
  import { colorActive, tagColor, cleanAccents, convertOrderBy } from "@/helpers/tools";
  import { mapActions, mapGetters } from "vuex";
  import CardTable from "@/components/card/CardTable.vue";
  import PaginationTable from "@/components/pagination/PaginationTable";
  export default {
    components: { CardTable, PaginationTable },
    metaInfo: {
      title: "Đánh giá",
    },
    data() {
      return {
        title: "Đánh giá tour",
        pagination: {
          total: 0,
          perPage: 0,
          current: 1
        },
        sorter: {},
        q: ""
      };
    },
    computed: {
      ...mapGetters("rating", ["loading", "ratings"]),
      columns() {
        const columns = [
          {
            title: "#No",
            width: "6%",
            scopedSlots: { customRender: "no" }
          },
          {
            title: "Tour",
            dataIndex: "tour",
            width: "20%"
          },
          {
            title: "Người đánh giá",
            dataIndex: "author",
            width: "15%"
          },
          {
            title: "Điểm",
            dataIndex: "scores",
            sorter: true,
            width: "9%",
            scopedSlots: { customRender: "scores" }
          },
          {
            title: "Nội dung",
            dataIndex: "content"
          },
          {
            title: "Ngày đánh giá",
            dataIndex: "created_at",
            width: "15%",
            sorter: true
          },
          {
            title: "Ẩn/hiện",
            width: "5%",
            scopedSlots: { customRender: "active" }
          },
          {
            title: "Tùy chọn",
            key: "action",
            width: "10%",
            align: "center",
            scopedSlots: { customRender: "action" }
          }
        ];
        return columns;
      }
    },
    created() {
      this.fetchData();
    },
    methods: {
      ...mapActions("rating", ["fetchRatings", "updateRating", "deleteRating"]),
      async fetchData(params = {}) {
        const pagination = { ...this.pagination };
        const { data } = await this.fetchRatings(params);
        pagination.total = data.meta.total;
        pagination.perPage = data.meta.per_page;
        pagination.current = data.meta.current_page;
        this.pagination = pagination;
      },
      onTableChange(pagination, filters, sorter) {
        const pager = { ...this.pagination };
        const page = pager.current;
        const perPage = pager.perPage;
        const sortBy = sorter.field;
        const orderBy = convertOrderBy(sorter.order);
        this.fetchData({ page, perPage, sortBy, orderBy });
        this.sorter = sorter;
      },
      onShowSizeChange({ current, pageSize }) {
        const sorter = { ...this.sorter };
        const sortBy = sorter.field;
        const orderBy = convertOrderBy(sorter.order);
        const page = current;
        const perPage = pageSize;
        this.fetchData({ page, perPage, sortBy, orderBy });
      },
      onChange({ page, pageSize }) {
        const sorter = { ...this.sorter };
        const sortBy = sorter.field;
        const orderBy = convertOrderBy(sorter.order);
        const perPage = pageSize;
        this.fetchData({ page, perPage, sortBy, orderBy });
      },
      reset() {
        this.fetchData();
        this.pagination.current = 1;
      },
      onClickActive(is_active, e) {
        const id = +e.target.name.replace("r__", "");
        this.updateRating({ id, values: { is_active } });
      },
      onDelete(ratingId) {
        this.deleteRating(ratingId);
        this.fetchData();
      }
    }
  };
</script>

<style></style>
