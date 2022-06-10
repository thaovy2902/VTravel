<template>
  <div>
    <card-table :title="title" :add-button="true" :no-search="true" @open="visible = true" @reset="reset">
      <a-table
        size="middle"
        :columns="columns"
        :loading="loading"
        :rowKey="(record) => record.id"
        :dataSource="discountCodes"
        :pagination="false"
        @change="onTableChange"
      >
        <strong slot="no" slot-scope="text, record, index">{{ ++index }}</strong>
        <a-tag color="blue" slot="code" slot-scope="text">{{ text }}</a-tag>
        <span slot="percent" slot-scope="text">{{ text | percent }}</span>
        <span slot="expire" slot-scope="text">{{ text | date }}</span>
        <span slot="created_at" slot-scope="text">{{ text | dateTime }}</span>
        <template slot="action" slot-scope="text, record">
          <a-button size="small" :disabled="disabledButton(record)" @click="sendMail(record.id)">Gửi đến mail</a-button>
        </template>
      </a-table>

      <pagination-table
        :total="pagination.total"
        :pageSize="pagination.per_page"
        :current="pagination.current_page"
        @onShowSizeChange="onShowSizeChange"
        @onChange="onChange"
      />
    </card-table>

    <drawer-table :visible="visible" :loading-button="loadingButton" @saveForm="saveForm" @closeForm="closeForm">
      <a-form :form="form" layout="vertical" @submit.prevent="saveForm" hideRequiredMark>
        <a-form-item label="Phần trăm" hasFeedback>
          <a-input-number v-decorator="['percent', config]" :min="0" :max="99" placeholder="Phần trăm" style="width:100%" />
        </a-form-item>
        <a-form-item label="Lượt sử dụng" hasFeedback>
          <a-input-number v-decorator="['remaining', config]" :min="0" placeholder="Lượt sử dụng" style="width:100%" />
        </a-form-item>
        <a-form-item label="Hạn dùng" hasFeedback>
          <a-date-picker
            v-decorator="['expire', configDate]"
            :disabledDate="disabledDate"
            allowClear
            placeholder="Hạn dùng"
            style="width:100%"
          />
        </a-form-item>
        <a-form-item label="Số lượng" extra="Không chọn nếu bạn tạo 1 mã">
          <a-select v-decorator="['quantity']" placeholder="Số lượng" style="width:100%">
            <a-select-option value="5">5</a-select-option>
            <a-select-option value="10">10</a-select-option>
            <a-select-option value="15">15</a-select-option>
            <a-select-option value="20">20</a-select-option>
          </a-select>
        </a-form-item>
      </a-form>
    </drawer-table>

    <a-modal
      centered
      title="Số khách hàng"
      okText="Gửi"
      cancelText="Đóng"
      :width="400"
      :visible="isVisibleModal"
      @cancel="onCancel"
      @ok="onOk"
    >
      <a-form-item label="Số khách hàng">
        <a-select placeholder="Chọn số khách hàng" style="width: 100%" v-model="numberUser">
          <a-select-option value="5">5</a-select-option>
          <a-select-option value="10">10</a-select-option>
        </a-select>
      </a-form-item>
    </a-modal>
  </div>
</template>

<script>
  import { mapActions, mapState } from "vuex";
  import { convertOrderBy } from "@/helpers/tools";
  import CardTable from "@/components/card/CardTable.vue";
  import PaginationTable from "@/components/pagination/PaginationTable";
  import DrawerTable from "@/components/drawer/DrawerTable";
  export default {
    components: { CardTable, PaginationTable, DrawerTable },
    metaInfo: {
      title: "Mã giảm giá",
    },
    data() {
      return {
        title: "Mã giảm giá",
        pagination: {
          total: 0,
          per_page: 0,
          current_page: 1,
        },
        sorter: {},
        visible: false,
        isVisibleModal: false,
        loadingButton: false,
        discountCodeId: "",
        numberUser: undefined,
        config: { rules: [{ type: "number", required: true, message: "Không được trống" }] },
        configDate: { rules: [{ type: "object", required: true, message: "Không được trống" }] },
      };
    },
    computed: {
      ...mapState("discountCode", ["discountCodes", "loading"]),
      columns() {
        const columns = [
          {
            title: "#No",
            scopedSlots: { customRender: "no" },
          },
          {
            title: "Mã",
            dataIndex: "code",
            sorter: true,
            scopedSlots: { customRender: "code" },
          },
          {
            title: "Giảm",
            dataIndex: "percent",
            sorter: true,
            scopedSlots: { customRender: "percent" },
          },
          {
            title: "Còn lại",
            sorter: true,
            dataIndex: "remaining",
          },
          {
            title: "Hạn dùng",
            dataIndex: "expire",
            sorter: true,
            scopedSlots: { customRender: "expire" },
          },
          {
            title: "Ngày tạo",
            dataIndex: "created_at",
            sorter: true,
            scopedSlots: { customRender: "created_at" },
          },
          {
            title: "Tùy chọn",
            scopedSlots: { customRender: "action" },
          },
        ];
        return columns;
      },
    },
    beforeCreate() {
      this.form = this.$form.createForm(this, { name: "form_discount_code" });
    },
    created() {
      this.fetchData();
    },
    methods: {
      ...mapActions("discountCode", ["fetchDiscountCodes", "createDiscountCode", "sendMailDiscountCode"]),
      async fetchData(params = {}) {
        const pagination = { ...this.pagination };
        const { total, current_page, per_page } = await this.fetchDiscountCodes(params);
        pagination.total = total;
        pagination.current_page = current_page;
        pagination.per_page = per_page;
        this.pagination = pagination;
      },
      onTableChange(pagination, filters, sorter) {
        const pager = { ...this.pagination };
        const page = pager.current_page;
        const perPage = pager.per_page;
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
      disabledDate(current) {
        return current && current < new Date(Date.now() - 864e5);
      },
      saveForm() {
        this.form.validateFields(async (err, values) => {
          if (!err) {
            this.loadingButton = true;
            values.expire = values["expire"].format("YYYY-MM-DD");
            values.quantity = values.quantity || "";
            await this.createDiscountCode(values);
            this.loadingButton = false;
            this.closeForm();
          }
        });
      },
      closeForm() {
        this.visible = false;
        this.form.resetFields();
      },
      disabledButton(record) {
        let { remaining, expire } = record;
        let curDate = new Date().toISOString().slice(0, 10);
        if (remaining == 0 || expire < curDate) {
          return true;
        }
        return false;
      },
      sendMail(id) {
        this.discountCodeId = id;
        this.isVisibleModal = true;
      },
      onCancel() {
        this.numberUser = undefined;
        this.isVisibleModal = false;
      },
      async onOk() {
        const { numberUser, discountCodeId } = this;
        if (numberUser === undefined || discountCodeId == "") {
          return;
        }
        try {
          this.isVisibleModal = false;
          await this.sendMailDiscountCode({ discountCodeId, numberUser });
        } finally {
          this.numberUser = undefined;
          this.discountCodeId = "";
        }
      },
    },
  };
</script>

<style>
  .ant-form-inline .ant-form-item-with-help {
    margin-bottom: 0 !important;
  }
</style>
