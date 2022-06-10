<template>
  <div>
    <card-table placeholder="Tìm kiếm theo tên, email" :title="title" :add-button="true" @open="open" @reset="reset" @search="search">
      <a-table
        :columns="columns"
        :loading="loading"
        :rowKey="(record) => record.id"
        :dataSource="users"
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
            :name="`u__${record.id}`"
            :checked="record.is_active"
            :defaultChecked="record.is_active"
            @click="onClickActive"
          >
          </a-switch>
        </template>
        <template slot="role_name" slot-scope="record">
          <a-tag color="green">{{ record }}</a-tag>
        </template>
        <template slot="action" slot-scope="record">
          <a-button type="dashed" size="small" @click="onUpdate(record.id)">
            <a-icon type="edit"></a-icon>
          </a-button>
          <a-divider type="vertical" />
          <a-popconfirm v-if="users.length" title="Bạn có chắc chắn?" @confirm="onDelete(record.id)">
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

    <drawer-table :visible="visible" :loading-button="loadingButton" :edit-mode="editMode" @saveForm="saveForm" @closeForm="closeForm">
      <a-form :form="form" layout="vertical" @submit.prevent="saveForm" hideRequiredMark>
        <a-form-item label="Họ tên" has-feedback>
          <a-input
            v-decorator="[
              'name',
              {
                rules: [
                  { required: true, message: 'Không được trống' },
                  {
                    whitespace: true,
                    message: 'Không được nhập khoảng trắng',
                  },
                  { max: 255, message: 'Tối đa 255 ký tự' },
                ],
              },
            ]"
            placeholder="Nhập họ tên"
          />
        </a-form-item>
        <a-form-item label="Email" :has-feedback="!editMode">
          <a-input
            :disabled="editMode"
            v-decorator="[
              'email',
              {
                rules: [
                  { type: 'email', message: 'Không đúng định dạng email' },
                  { required: true, message: 'Không được trống' },
                  { max: 255, message: 'Tối đa 255 ký tự' },
                ],
              },
            ]"
            placeholder="Nhập email"
          />
        </a-form-item>
        <a-form-item v-if="!editMode" label="Mật khẩu" :has-feedback="!editMode">
          <a-input
            type="password"
            v-decorator="[
              'password',
              {
                rules: [
                  { required: true, message: 'Không được trống' },
                  {
                    whitespace: true,
                    message: 'Không được nhập khoảng trắng',
                  },
                  { min: 6, message: 'Tối thiểu 6 ký tự' },
                  { max: 255, message: 'Tối đa 255 ký tự' },
                ],
              },
            ]"
            placeholder="Nhập mật khẩu"
          />
        </a-form-item>
        <a-form-item label="Số điện thoại">
          <a-input
            v-decorator="[
              'phone_number',
              {
                rules: [{ max: 12, message: 'Tối đa 12 ký tự' }],
              },
            ]"
            placeholder="Nhập số điện thoại"
          />
        </a-form-item>
        <a-form-item label="Địa chỉ">
          <a-textarea v-decorator="['address']" placeholder="Nhập địa chỉ" :autosize="{ minRows: 2, maxRows: 3 }" />
        </a-form-item>
        <a-form-item label="Ẩn/hiện">
          <a-switch v-decorator="['is_active']" v-model="checked"> </a-switch>
        </a-form-item>
      </a-form>
    </drawer-table>
  </div>
</template>

<script>
  import { colorActive, cleanAccents, convertOrderBy } from "@/helpers/tools";
  import { mapActions, mapGetters } from "vuex";
  import CardTable from "@/components/card/CardTable.vue";
  import PaginationTable from "@/components/pagination/PaginationTable";
  import DrawerTable from "@/components/drawer/DrawerTable";
  export default {
    components: { CardTable, PaginationTable, DrawerTable },
    metaInfo: {
      title: "Khách hàng",
    },
    data() {
      return {
        title: "Khách hàng",
        pagination: {
          total: 0,
          perPage: 0,
          current: 1,
        },
        sorter: {},
        q: "",
        //form
        visible: false,
        editMode: false,
        updateId: "",
        checked: true,
        loadingButton: false,
      };
    },
    computed: {
      ...mapGetters("user", ["loading", "users", "getUserById"]),
      columns() {
        const columns = [
          {
            title: "#No",
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
            key: "status",
            width: "13%",
            scopedSlots: { customRender: "status" },
          },
          {
            title: "Ẩn/hiện",
            align: "center",
            scopedSlots: { customRender: "active" },
          },
          {
            title: "Quyền",
            dataIndex: "role_name",
            align: "center",
            scopedSlots: { customRender: "role_name" },
          },
          {
            title: "Tùy chọn",
            key: "action",
            align: "center",
            width: "13%",
            scopedSlots: { customRender: "action" },
          },
        ];
        return columns;
      },
    },
    created() {
      this.fetchData();
      this.initForm();
    },
    methods: {
      ...mapActions("user", ["fetchUsers", "createUser", "updateUser", "updateActiveUser", "deleteUser"]),
      async fetchData(params = {}) {
        const pagination = { ...this.pagination };
        const { data } = await this.fetchUsers(params);
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
        const id = +e.target.name.replace("u__", "");
        this.updateActiveUser({ id, values: { is_active } });
      },
      colorActive(value) {
        return colorActive(value);
      },
      open() {
        this.editMode = false;
        this.visible = true;
      },
      onUpdate(userId) {
        const user = this.getUserById(userId);
        this.form.setFieldsValue(user);
        this.updateId = userId;
        this.checked = user.is_active;
        this.visible = this.editMode = true;
      },
      onDelete(userId) {
        this.deleteUser(userId);
        this.fetchData();
      },
      //Handle with form
      initForm() {
        this.form = this.$form.createForm(this);
        this.form.getFieldDecorator("name", { initialValue: "" });
        this.form.getFieldDecorator("email", { initialValue: "" });
        this.form.getFieldDecorator("password", { initialValue: "" });
        this.form.getFieldDecorator("phone_number", { initialValue: "" });
        this.form.getFieldDecorator("address", { initialValue: "" });
        this.form.getFieldDecorator("is_active", { initialValue: true });
      },
      saveForm() {
        this.form.validateFields((err, values) => {
          if (!err) {
            if (!this.editMode) {
              this.loadingButton = true;
              this.createUser(values);
              this.loadingButton = false;
              this.closeForm();
              this.reset();
            } else {
              this.loadingButton = true;
              this.updateUser({
                id: this.updateId,
                values,
              });
              this.loadingButton = false;
              this.closeForm();
            }
          }
        });
      },
      closeForm() {
        this.visible = this.editMode = false;
        this.form.resetFields();
      },
    },
  };
</script>

<style></style>
