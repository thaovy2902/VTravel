<template>
  <div>
    <card-table :title="title" :add-button="true" @open="open" @reset="reset" :noSearch="true">
      <a-table :columns="columns" :loading="loading" :rowKey="record => record.id" :dataSource="slides" :pagination="false">
        <strong slot="no" slot-scope="text, record, index">{{ ++index }}</strong>
        <template slot="image" slot-scope="record">
          <img v-lazy="record.image" :alt="record.title" width="100%" />
        </template>
        <template slot="active" slot-scope="record">
          <a-switch
            size="small"
            :key="record.id"
            :name="`s__${record.id}`"
            :checked="record.is_active"
            :defaultChecked="record.is_active"
            @click="onClickActive"
          >
          </a-switch>
        </template>
        <template slot="action" slot-scope="record">
          <a-button type="dashed" size="small" @click="onUpdate(record.id)">
            <a-icon type="edit"></a-icon>
          </a-button>
          <a-divider type="vertical" />
          <a-popconfirm v-if="slides.length" title="Bạn có chắc chắn?" @confirm="onDelete(record.id)">
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

    <drawer-table :visible="visible" :edit-mode="editMode" @saveForm="saveForm" @closeForm="closeForm">
      <a-form :form="form" layout="vertical" @submit.prevent="saveForm" hideRequiredMark>
        <a-form-item label="Tiêu đề">
          <a-input
            v-decorator="[
              'title',
              {
                rules: [
                  { required: true, message: 'Không được trống' },
                  { max: 255, message: 'Tối đa 255 ký tự' }
                ]
              }
            ]"
            placeholder="Nhập tiêu đề"
          />
        </a-form-item>
        <a-form-item label="Link">
          <a-input
            v-decorator="[
              'link',
              {
                rules: [
                  { required: true, message: 'Không được trống' },
                  { max: 255, message: 'Tối đa 255 ký tự' }
                ]
              }
            ]"
            placeholder="Nhập link (nếu có)"
          />
        </a-form-item>
        <a-form-item label="Ảnh">
          <div v-if="!imagePreview">
            <input type="file" ref="image" accept="image/*" :style="{ display: 'none' }" @change="onChangeImage" />
            <a-button type="dashed" icon="plus" block @click="$refs.image.click()">Ảnh</a-button>
          </div>
          <div v-else>
            <img :src="imagePreview" class="rounded w-100 mb-2" />
            <a-button type="danger" icon="delete" block @click="onClickClearImage">Xóa</a-button>
          </div>
        </a-form-item>
        <a-form-item label="Hide/Show">
          <a-switch v-decorator="['is_active']" v-model="checked"> </a-switch>
        </a-form-item>
      </a-form>
    </drawer-table>

    <image-cropper :visibleModal="visibleModal" :imageUrl="imageUrl" @onCloseModal="onCloseModal" @getImage="getImage" />
  </div>
</template>

<script>
  import { mapActions, mapGetters } from "vuex";
  import { cleanAccents } from "@/helpers/tools";
  import { uploadImage } from "@/services/upload";
  import CardTable from "@/components/card/CardTable.vue";
  import PaginationTable from "@/components/pagination/PaginationTable";
  import DrawerTable from "@/components/drawer/DrawerTable";
  export default {
    components: { CardTable, PaginationTable, DrawerTable },
    metaInfo: {
      title: "Slide",
    },
    data() {
      return {
        title: "Slide",
        pagination: {
          total: 0,
          perPage: 0,
          current: 1
        },
        q: "",
        //form
        visible: false,
        editMode: false,
        checked: true,
        updateId: "",
        //image cropper
        visibleModal: false,
        imagePreview: "",
        imageUrl: "",
        imageUpload: ""
      };
    },
    computed: {
      ...mapGetters("slide", ["loading", "slides", "getSlideById"]),
      columns() {
        const columns = [
          {
            title: "#No",
            width: "6%",
            scopedSlots: { customRender: "no" }
          },
          {
            title: "Hình",
            align: "center",
            scopedSlots: { customRender: "image" }
          },
          {
            title: "Tiêu đề",
            dataIndex: "title",
            width: "25%"
          },
          {
            title: "Link",
            dataIndex: "link",
            width: "25%",
            scopedSlots: { customRender: "link" }
          },
          {
            title: "Hide/Show",
            key: "active",
            align: "center",
            width: "5%",
            scopedSlots: { customRender: "active" }
          },
          {
            title: "Option",
            align: "center",
            width: "13%",
            scopedSlots: { customRender: "action" }
          }
        ];
        return columns;
      }
    },
    created() {
      this.fetchData();
      this.initForm();
    },
    methods: {
      ...mapActions("slide", ["fetchSlides", "createSlide", "updateSlide", "updateActiveSlide", "deleteSlide"]),
      async fetchData(params = {}) {
        const pagination = { ...this.pagination };
        const { data } = await this.fetchSlides(params);
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
        const id = +e.target.name.replace("s__", "");
        this.updateActiveSlide({ id, values: { is_active } });
      },
      open() {
        this.editMode = false;
        this.visible = true;
      },
      onUpdate(slideId) {
        const slide = this.getSlideById(slideId);
        this.updateId = slideId;
        this.checked = slide.is_active;
        this.imagePreview = slide.image;
        this.form.setFieldsValue(slide);
        this.visible = this.editMode = true;
      },
      onDelete(slideId) {
        this.deleteSlide(slideId);
        this.fetchData();
      },
      //Handle with form
      initForm() {
        this.form = this.$form.createForm(this);
        this.form.getFieldDecorator("title", { initialValue: "" });
        this.form.getFieldDecorator("link", { initialValue: "" });
        this.form.getFieldDecorator("image", { initialValue: "" });
        this.form.getFieldDecorator("is_active", { initialValue: true });
      },
      saveForm() {
        this.form.validateFields((err, values) => {
          if (!err) {
            if (!this.editMode) {
              if (!this.imageUpload) {
                this.$message.error("Bạn chưa chọn hình");
                return;
              }
              uploadImage("slides", this.imageUpload).then(url => {
                values.image = url;
                this.createSlide(values);
                this.closeForm();
                this.reset();
              });
            } else {
              if (this.imageUpload) {
                uploadImage("slides", this.imageUpload).then(url => {
                  values.image = url;
                  this.updateSlide({
                    id: this.updateId,
                    values
                  });
                  this.closeForm();
                });
              } else {
                this.updateSlide({
                  id: this.updateId,
                  values
                });
                this.closeForm();
              }
            }
          }
        });
      },
      closeForm() {
        this.checked = this.visible = this.editMode = false;
        this.onClickClearImage();
        this.form.resetFields();
      },
      //image cropper
      onChangeImage(e) {
        const file = e.target.files[0];
        this.imageUrl = URL.createObjectURL(file);
        this.visibleModal = true;
      },
      onCloseModal() {
        this.imageUrl = "";
        this.visibleModal = false;
      },
      getImage(data) {
        this.imagePreview = URL.createObjectURL(data);
        this.imageUpload = new File([data], "file.webp", { type: "image/webp" });
      },
      onClickClearImage() {
        this.imagePreview = this.imageUpload = "";
      }
    }
  };
</script>

<style></style>
