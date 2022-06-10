<template>
  <card-table :title="title" :no-search="true" @reset="reset">
    <a-table
      size="middle"
      :columns="columns"
      :loading="loading"
      :rowKey="record => record.id"
      :dataSource="feedbacks"
      :pagination="false"
      @change="onTableChange"
    >
      <strong slot="no" slot-scope="text, record, index">{{ ++index }}</strong>
      <template slot="info" slot-scope="text, record">
        <div>{{ record.email }}</div>
        <div>{{ record.phone_number }}</div>
      </template>
      <span slot="type" slot-scope="text" style="fontWeight:500">{{ text | typeFeedback }}</span>
      <template slot="seen" slot-scope="record">
        <a-switch
          size="small"
          :key="record.id"
          :name="`f__${record.id}`"
          :checked="record.is_seen"
          :defaultChecked="record.is_seen"
          @click="onClickSeen"
        >
        </a-switch>
      </template>
      <template slot="action" slot-scope="record">
        <a-popconfirm v-if="feedbacks.length" title="Bạn có chắc chắn?" @confirm="onDelete(record.id)">
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
  import { convertOrderBy } from "@/helpers/tools";
  import { mapActions, mapGetters } from "vuex";
  import CardTable from "@/components/card/CardTable.vue";
  import PaginationTable from "@/components/pagination/PaginationTable";
  export default {
    components: { CardTable, PaginationTable },
    metaInfo: {
      title: "Phản hồi",
    },
    data() {
      return {
        title: "Phản hồi",
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
      ...mapGetters("feedback", ["loading", "feedbacks"]),
      columns() {
        const columns = [
          {
            title: "#No",
            width: "5%",
            scopedSlots: { customRender: "no" }
          },
          {
            title: "Họ tên",
            dataIndex: "name",
            width: "15%",
            sorter: true
          },
          {
            title: "Thông tin",
            width: "15%",
            scopedSlots: { customRender: "info" }
          },
          {
            title: "Loại",
            dataIndex: "type",
            width: "10%",
            scopedSlots: { customRender: "type" }
          },
          {
            title: "Nội dung",
            dataIndex: "content"
          },
          {
            title: "Ngày gửi",
            dataIndex: "created_at",
            width: "12%",
            sorter: true
          },
          {
            title: "Đã xem",
            width: "9%",
            align: "center",
            scopedSlots: { customRender: "seen" }
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
      ...mapActions("feedback", ["fetchFeedbacks", "updateFeedback", "deleteFeedback"]),
      async fetchData(params = {}) {
        const pagination = { ...this.pagination };
        const { data } = await this.fetchFeedbacks(params);
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
      onClickSeen(is_seen, e) {
        const id = +e.target.name.replace("f__", "");
        this.updateFeedback({ id, values: { is_seen } });
      },
      onDelete(feedbackId) {
        this.deleteFeedback(feedbackId);
        this.reset();
      }
    }
  };
</script>

<style></style>
