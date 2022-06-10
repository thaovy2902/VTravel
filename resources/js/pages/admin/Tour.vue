<template>
  <div>
    <card-table placeholder="Tìm kiếm theo tên, mã tour" :title="title" :add-button="true" @open="open" @reset="reset" @search="search">
      <a-table
        :columns="columns"
        :loading="loading"
        :rowKey="record => record.id"
        :dataSource="tours"
        :pagination="false"
        @change="onTableChange"
      >
        <strong slot="no" slot-scope="text, record, index">{{ ++index }}</strong>
        <template slot="code" slot-scope="record">
          <a-tooltip placement="top" title="Click để xem chi tiết">
            <a class="color-code" @click="onPreview(record.id)">#{{ record.code }}</a>
          </a-tooltip>
        </template>
        <template slot="from_to_place" slot-scope="record">
          <span>{{ record.from_place_name }} - {{ record.to_place_name }}</span>
        </template>
        <template slot="status" slot-scope="record">
          <a-tag :color="colorActive(record)">{{ record | status }}</a-tag>
        </template>
        <template slot="featured" slot-scope="record">
          <a-switch
            size="small"
            :key="record.id"
            :name="`t_f_${record.id}`"
            :checked="record.is_featured"
            :defaultChecked="record.is_featured"
            @click="onClickFeatured"
          >
          </a-switch>
        </template>
        <template slot="active" slot-scope="record">
          <a-switch
            size="small"
            :key="record.id"
            :name="`t_a_${record.id}`"
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
          <a-popconfirm v-if="tours.length" title="Bạn có chắc chắn?" @confirm="onDelete(record.id)">
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

    <drawer-preview v-if="tour" :visiblePreview="visiblePreview" :data="tour" @closeDrawerPreview="closeDrawerPreview" />

    <drawer-table
      :width="720"
      :visible="visible"
      :edit-mode="editMode"
      :loading-button="loadingButton"
      @saveForm="saveForm"
      @closeForm="closeForm"
    >
      <a-form :form="form" layout="vertical" @submit.prevent="saveForm" hideRequiredMark>
        <a-row :gutter="16">
          <a-col :span="12">
            <a-form-item label="Tên tour" has-feedback>
              <a-input
                v-decorator="[
                  'name',
                  {
                    rules: [
                      { required: true, message: 'Không được trống' },
                      { max: 255, message: 'Tối đa 255 ký tự' }
                    ]
                  }
                ]"
                placeholder="Nhập tên tour"
              />
            </a-form-item>
          </a-col>
          <a-col :span="12">
            <a-form-item label="Loại tour" has-feedback>
              <a-select
                v-decorator="[
                  'category_id',
                  {
                    rules: [{ required: true, message: 'Không được trống' }]
                  }
                ]"
                placeholder="Chọn loại tour"
                @change="onChangeCategory"
              >
                <a-select-option v-for="cate in categories" :key="cate.id" :value="cate.id">
                  {{ cate.name }}
                </a-select-option>
              </a-select>
            </a-form-item>
          </a-col>
        </a-row>

        <a-row :gutter="16">
          <a-col :span="12">
            <a-form-item label="Nơi đi" has-feedback>
              <a-input
                v-decorator="[
                  'from_place_name',
                  {
                    rules: [{ required: true, message: 'Không được trống' }]
                  }
                ]"
                placeholder="Nhập nơi đi"
              />
            </a-form-item>
          </a-col>
          <a-col :span="12">
            <a-form-item label="Nơi đến" has-feedback>
              <a-select
                mode="multiple"
                v-decorator="[
                  'to_place_code',
                  {
                    rules: [{ required: true, message: 'Không được trống', type: 'array' }]
                  }
                ]"
                placeholder="Chọn nơi đến"
              >
                <a-select-option v-for="city in cities" :key="city.code" :value="city.code">{{ city.name }}</a-select-option>
              </a-select>
            </a-form-item>
          </a-col>
        </a-row>

        <a-row :gutter="16">
          <a-col :span="12">
            <a-form-item label="Phương tiện" has-feedback>
              <a-input v-decorator="['transport']" placeholder="Nhập phương tiện" />
            </a-form-item>
          </a-col>
          <a-col :span="6">
            <a-form-item label="Số ngày">
              <a-input-number v-decorator="['number_days']" :defaultValue="1" :min="1" :max="30" style="width: 100%" />
            </a-form-item>
          </a-col>
          <a-col :span="6">
            <a-form-item label="Số người tối đa">
              <a-input-number v-decorator="['number_persons']" :defaultValue="1" :min="1" :max="20" style="width: 100%" />
            </a-form-item>
          </a-col>
        </a-row>

        <a-row :gutter="16">
          <a-col :span="6">
            <a-form-item label="Khởi hành" has-feedback>
              <a-select v-decorator="['depart', { rules: [{ required: true, message: 'Không được trống' }] }]" placeholder="Chọn">
                <a-select-option value="daily">Hằng ngày</a-select-option>
                <a-select-option value="contact">Liên hệ</a-select-option>
              </a-select>
            </a-form-item>
          </a-col>
          <a-col :span="6">
            <a-form-item label="Giá người lớn (đồng)">
              <a-input-number
                v-decorator="['price_default']"
                style="width: 100%"
                :min="0"
                :step="10000"
                :formatter="value => `${value}`.replace(/\B(?=(\d{3})+(?!\d))/g, ',')"
                :parser="value => value.replace(/(,*)/g, '')"
              />
            </a-form-item>
          </a-col>
          <a-col :span="6">
            <a-form-item label="Giá trẻ em (đồng)">
              <a-input-number
                v-decorator="['price_children']"
                style="width: 100%"
                :min="0"
                :step="10000"
                :formatter="value => `${value}`.replace(/\B(?=(\d{3})+(?!\d))/g, ',')"
                :parser="value => value.replace(/(,*)/g, '')"
              />
            </a-form-item>
          </a-col>
          <a-col :span="6">
            <a-form-item label="Giá em bé (đồng)">
              <a-input-number
                v-decorator="['price_baby']"
                style="width: 100%"
                :min="0"
                :step="10000"
                :formatter="value => `${value}`.replace(/\B(?=(\d{3})+(?!\d))/g, ',')"
                :parser="value => value.replace(/(,*)/g, '')"
              />
            </a-form-item>
          </a-col>
        </a-row>

        <a-row>
          <a-col>
            <a-form-item label="Mô tả">
              <CKEditor @sendEditorData="sendEditorData" />
              <a-input v-decorator="['description']" :style="{ display: 'none' }" />
            </a-form-item>
          </a-col>
          <a-col>
            <a-form-item label="Ghi chú">
              <a-textarea v-decorator="['note']" placeholder="Nhập ghi chú (nếu cần)" :autosize="{ minRows: 3, maxRows: 5 }" />
            </a-form-item>
          </a-col>
        </a-row>

        <a-row :gutter="16" :style="{ marginBottom: '24px' }">
          <a-col v-if="imagePreview">
            <img :src="imagePreview" class="rounded w-100 mb-2" />
          </a-col>
          <a-col>
            <input type="file" ref="image" accept="image/*" :style="{ display: 'none' }" @change="onChangeImage" />
            <input type="file" ref="gallery" accept="image/*" multiple :style="{ display: 'none' }" @change="onChangeGallery" />
            <a-button v-if="imagePreview" type="danger" icon="delete" block class="mb-2" @click="onClickClearImage">Xóa</a-button>
            <a-button v-if="!imagePreview" type="dashed" icon="picture" block class="mb-2" @click="$refs.image.click()">Image</a-button>
            <a-button type="dashed" icon="switcher" block @click="$refs.gallery.click()">Gallery</a-button>
            <a-row :gutter="16" :style="{ marginTop: '24px' }">
              <a-col :span="6">
                <a-form-item label="Nổi bật" :wrapperCol="{ span: 12 }" :labelCol="{ span: 12 }">
                  <a-switch v-decorator="['is_featured']" v-model="isFeatured"> </a-switch>
                </a-form-item>
              </a-col>
              <a-col :span="6">
                <a-form-item label="Ẩn/hiện" :wrapperCol="{ span: 12 }" :labelCol="{ span: 12 }">
                  <a-switch v-decorator="['is_active']" v-model="isActive"> </a-switch>
                </a-form-item>
              </a-col>
            </a-row>
          </a-col>
        </a-row>
      </a-form>
    </drawer-table>

    <image-cropper :visibleModal="visibleModal" :imageUrl="imageUrl" @onCloseModal="onCloseModal" @getImage="getImage" />
  </div>
</template>

<script>
  import { mapActions, mapGetters } from "vuex";
  import { colorActive, cleanAccents, convertOrderBy } from "@/helpers/tools";
  import { uploadImage } from "@/services/upload";
  import CardTable from "@/components/card/CardTable.vue";
  import PaginationTable from "@/components/pagination/PaginationTable";
  import DrawerTable from "@/components/drawer/DrawerTable";
  import DrawerPreview from "@/components/tour/DrawerPreview";
  import CKEditor from "@/components/ckeditor/CKEditor";

  export default {
    components: { CardTable, PaginationTable, DrawerTable, DrawerPreview, CKEditor },
    metaInfo: {
      title: "Tour",
    },
    data() {
      return {
        title: "Tour",
        pagination: {
          total: 0,
          perPage: 0,
          current: 1
        },
        sorter: {},
        q: "",
        //form
        visible: false,
        editMode: false,
        isActive: true,
        isFeatured: false,
        updateId: "",
        categoryId: "",
        imageUpload: null,
        galleryUpload: [],
        loadingButton: false,
        //preview
        visiblePreview: false,
        tour: null,
        //image cropper
        visibleModal: false,
        imageUrl: "",
        imagePreview: ""
      };
    },
    computed: {
      ...mapGetters("tour", ["loading", "tours", "categories", "getTourById"]),
      columns() {
        const columns = [
          {
            title: "#No",
            width: "6%",
            scopedSlots: { customRender: "no" }
          },
          {
            title: "Mã tour",
            width: "15%",
            scopedSlots: { customRender: "code" }
          },
          {
            title: "Tên tour",
            dataIndex: "name",
            sorter: true
          },
          {
            title: "Nơi đi/nơi đến",
            scopedSlots: { customRender: "from_to_place" }
          },
          {
            title: "Loại tour",
            dataIndex: "category_name"
          },
          {
            title: "Trạng thái",
            dataIndex: "is_active",
            key: "status",
            width: "13%",
            scopedSlots: { customRender: "status" }
          },
          {
            title: "Nổi bật",
            align: "center",
            width: "7%",
            scopedSlots: { customRender: "featured" }
          },
          {
            title: "Ẩn/hiện",
            align: "center",
            width: "5%",
            scopedSlots: { customRender: "active" }
          },
          {
            title: "Tùy chọn",
            key: "action",
            align: "center",
            width: "13%",
            scopedSlots: { customRender: "action" }
          }
        ];
        return columns;
      },
      cities() {
        if (!this.categoryId) {
          return this.$root.cities;
        }
        if (this.categoryId == 1) {
          return this.$root.cities.filter(item => item.code < 100);
        }
        if (this.categoryId == 2) {
          return this.$root.cities.filter(item => item.code >= 100);
        }
      }
    },
    created() {
      this.fetchData();
      this.fetchCategories();
      this.initForm();
    },
    methods: {
      ...mapActions("tour", ["fetchCategories", "fetchTours", "createTour", "updateTour", "updateStatusTour", "deleteTour"]),
      async fetchData(params = {}) {
        const pagination = { ...this.pagination };
        const { data } = await this.fetchTours(params);
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
      onClickFeatured(is_featured, e) {
        const id = +e.target.name.replace("t_f_", "");
        this.updateStatusTour({ id, values: { is_featured } });
      },
      onClickActive(is_active, e) {
        const id = +e.target.name.replace("t_a_", "");
        this.updateStatusTour({ id, values: { is_active } });
      },
      colorActive(value) {
        return colorActive(value);
      },
      open() {
        this.editMode = false;
        this.visible = true;
      },
      onUpdate(tourId) {
        const tour = this.getTourById(tourId);
        this.form.setFieldsValue(tour);
        eventBus.$emit("receiverDataEditor", tour.description);
        this.updateId = tourId;
        this.visible = this.editMode = true;
      },
      onDelete(tourId) {
        this.deleteTour(tourId);
        this.fetchData();
      },
      //Handle with form
      initForm() {
        this.form = this.$form.createForm(this);
        this.form.getFieldDecorator("name", { initialValue: "" });
        this.form.getFieldDecorator("from_place_name", { initialValue: "" });
        this.form.getFieldDecorator("to_place_code", { initialValue: undefined });
        this.form.getFieldDecorator("transport", { initialValue: "" });
        this.form.getFieldDecorator("number_days", { initialValue: 1 });
        this.form.getFieldDecorator("number_persons", { initialValue: 1 });
        this.form.getFieldDecorator("depart", { initialValue: undefined });
        this.form.getFieldDecorator("price_default", { initialValue: 0 });
        this.form.getFieldDecorator("price_children", { initialValue: 0 });
        this.form.getFieldDecorator("price_baby", { initialValue: 0 });
        this.form.getFieldDecorator("description", { initialValue: "" });
        this.form.getFieldDecorator("note", { initialValue: "" });
        this.form.getFieldDecorator("is_featured", { initialValue: false });
        this.form.getFieldDecorator("is_active", { initialValue: true });
        this.form.getFieldDecorator("category_id", { initialValue: undefined });
        this.form.getFieldDecorator("image", { initialValue: "" });
        this.form.getFieldDecorator("gallery", { initialValue: "" });
      },
      sendEditorData(data) {
        this.form.setFieldsValue({ description: data });
      },
      saveForm() {
        this.form.validateFields((err, values) => {
          if (!err) {
            const imageUpload = this.imageUpload;
            const galleryUpload = this.galleryUpload;
            const galleryUploadLength = galleryUpload.length;
            if (!this.editMode) {
              if (!imageUpload) {
                this.$message.error("Bạn chưa chọn hình");
                return;
              }
              this.loadingButton = true;
              uploadImage("tours", imageUpload).then(async url => {
                values.image = url;
                if (galleryUploadLength > 0) {
                  let gallery = [];
                  for (let i = 0; i < galleryUploadLength; i++) {
                    await uploadImage("tours", galleryUpload[i]).then(url => {
                      gallery.push(url);
                    });
                  }
                  values.gallery = gallery;
                }
                this.createTour(values);
                this.loadingButton = false;
                this.closeForm();
              });
            } else {
              if (imageUpload) {
                uploadImage("tours", imageUpload).then(async url => {
                  values.image = url;
                });
              }
              this.loadingButton = true;
              this.updateTour({
                id: this.updateId,
                values
              });
              this.loadingButton = false;
              this.closeForm();
            }
          }
        });
      },
      closeForm() {
        this.visible = this.editMode = false;
        this.imageUpload = null;
        this.galleryUpload.length = 0;
        this.onClickClearImage();
        this.form.resetFields();
        eventBus.$emit("emptyEditor", () => {});
      },

      //preview
      onPreview(tourId) {
        const tour = this.getTourById(tourId);
        this.tour = tour;
        this.visiblePreview = true;
      },
      closeDrawerPreview() {
        this.visiblePreview = false;
      },

      //image
      onChangeGallery(e) {
        const gallery = e.target.files;
        this.galleryUpload.push(...gallery);
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
      },
      onChangeCategory(value) {
        this.categoryId = value;
        this.form.setFieldsValue({ to_place_code: undefined });
      }
    }
  };
</script>

<style lang="less">
  .upload-box {
    display: table;
    width: 100%;
    min-height: 150px;
    text-align: center;
    vertical-align: top;
    background-color: #fafafa;
    border: 1px dashed #d9d9d9;
    border-radius: 4px;
    cursor: pointer;
    transition: border-color 0.3s ease;
  }
</style>
