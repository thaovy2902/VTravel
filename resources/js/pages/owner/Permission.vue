<template>
  <card-table placeholder="Tìm kiếm theo tên, email" :title="title" @reset="reset" @search="search">
    <a-table
      size="middle"
      :columns="columns"
      :loading="loading"
      :rowKey="(record) => record.id"
      :dataSource="permissions"
      :pagination="false"
      @change="onTableChange"
    >
      <strong slot="no" slot-scope="text, record, index">{{ ++index }}</strong>
      <template slot="avatar" slot-scope="text">
        <a-avatar v-if="text" :src="text" :size="32" />
        <a-avatar v-else icon="user" :size="32" />
      </template>
      <template slot="status" slot-scope="record">
        <a-tag :color="colorActive(record)">{{ record | status }}</a-tag>
      </template>
      <template slot="active" slot-scope="record">
        <a-switch
          size="small"
          :key="record.id"
          :name="`p__${record.id}`"
          :checked="record.is_active"
          :defaultChecked="record.is_active"
          @click="onClickActive"
        >
        </a-switch>
      </template>
      <template slot="action" slot-scope="record">
        <a-radio-group :name="`p__${record.id}`" :defaultValue="record.role_id" @change="onChangeRole">
          <a-radio v-for="role in roles" :key="role.id" :value="role.id" :style="{ margin: '0 4px' }">
            <a-tag :color="tagColor(role.slug)">
              {{ role.name }}
            </a-tag>
          </a-radio>
        </a-radio-group>
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
      title: "Phân quyền",
    },
    data() {
      return {
        title: "Phân quyền",
        pagination: {
          total: 0,
          perPage: 0,
          current: 1,
        },
        sorter: {},
        q: "",
      };
    },
    computed: {
      ...mapGetters("permission", ["loading", "permissions", "roles"]),
      columns() {
        const columns = [
          {
            title: "#No",
            align: "center",
            width: "5%",
            scopedSlots: { customRender: "no" },
          },
          {
            title: "Avatar",
            dataIndex: "avatar",
            scopedSlots: { customRender: "avatar" },
          },
          {
            title: "Họ tên",
            dataIndex: "name",
            sorter: true,
          },
          {
            title: "Email",
            dataIndex: "email",
            sorter: true,
          },
          {
            title: "Trạng thái",
            dataIndex: "is_active",
            width: "12%",
            sorter: true,
            scopedSlots: { customRender: "status" },
          },
          {
            title: "Active",
            align: "center",
            width: "7%",
            scopedSlots: { customRender: "active" },
          },
          {
            title: "Quyền",
            align: "center",
            width: "23%",
            scopedSlots: { customRender: "action" },
          },
        ];
        return columns;
      },
    },
    created() {
      this.fetchData();
      this.fetchRoles();
    },
    methods: {
      ...mapActions("permission", ["fetchPermissions", "fetchRoles", "updatePermission"]),
      async fetchData(params = {}) {
        const pagination = { ...this.pagination };
        const { data } = await this.fetchPermissions(params);
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
        const q = this.q;
        this.fetchData({ page, perPage, sortBy, orderBy, q });
        this.sorter = sorter;
      },
      onShowSizeChange({ current, pageSize }) {
        const sorter = { ...this.sorter };
        const sortBy = sorter.field;
        const orderBy = convertOrderBy(sorter.order);
        const q = this.q;
        const page = current;
        const perPage = pageSize;
        this.fetchData({ page, perPage, sortBy, orderBy, q });
      },
      onChange({ page, pageSize }) {
        const sorter = { ...this.sorter };
        const sortBy = sorter.field;
        const orderBy = convertOrderBy(sorter.order);
        const q = this.q;
        const perPage = pageSize;
        this.fetchData({ page, perPage, sortBy, orderBy, q });
      },
      search(value) {
        if (value) {
          const pager = { ...this.pagination };
          const sorter = { ...this.sorter };
          const orderBy = convertOrderBy(sorter.order);
          const page = pager.current;
          const perPage = pager.perPage;
          const sortBy = sorter.field;
          const q = cleanAccents(value);
          this.fetchData({ page, perPage, sortBy, orderBy, q });
          this.q = q;
        }
      },
      reset() {
        this.fetchData();
        this.pagination.current = 1;
        this.q = "";
      },
      onClickActive(is_active, e) {
        const id = +e.target.name.replace("p__", "");
        this.updatePermission({ id, values: { is_active } });
      },
      onChangeRole(e) {
        const id = +e.target.name.replace("p__", "");
        const role_id = e.target.value;
        this.updatePermission({ id, values: { role_id } });
      },
      tagColor(value) {
        return tagColor(value);
      },
      colorActive(value) {
        return colorActive(value);
      },
    },
  };
</script>

<style></style>
