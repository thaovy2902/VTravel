<template>
  <div>
    <loading-full-screen v-if="loading" />
    <a-card v-else class="shadow-sm" :bordered="false">
      <a-row :gutter="24">
        <a-col :span="24" class="title">
          Thông tin tài khoản
          <a-divider />
        </a-col>
        <a-col v-if="!$auth.user.phone_number && !$auth.user.address" :span="24" style="margin-bottom:16px">
          <a-alert type="info" message="Bạn nên cập nhật đầy đủ thông tin" banner />
        </a-col>
        <a-col :xs="24" :sm="24" :md="8" :lg="8">
          <a-row type="flex" justify="center" align="middle">
            <a-col v-if="$auth.user.avatar" :span="20">
              <div class="container-avatar" @click="$refs.inputAvatar.click()">
                <img :src="$auth.user.avatar" :alt="$auth.user.avatar" />
              </div>
            </a-col>
            <a-col v-else :span="24" style="text-align:center">
              Chưa có ảnh đại diện. Vui lòng chọn ảnh
            </a-col>
            <a-col style="margin-top:12px">
              <input ref="inputAvatar" type="file" accept="image/*" style="display:none" @change="onChangeAvatar" />
              <a-button type="dashed" size="small" icon="upload" @click="$refs.inputAvatar.click()">Chọn ảnh</a-button>
            </a-col>
          </a-row>
        </a-col>
        <a-col :xs="24" :sm="24" :md="16" :lg="16">
          <a-row type="flex" justify="center" align="middle">
            <a-col :span="18">
              <a-form :form="formUserData" v-bind="formItemLayout" layout="vertical" @submit.prevent="saveForm" hideRequiredMark>
                <a-form-item label="Email">
                  <a-input :value="user.email" disabled />
                </a-form-item>
                <a-form-item label="Họ tên">
                  <a-input
                    v-decorator="[
                      'name',
                      {
                        initialValue: user.name,
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
                    :disabled="disabled"
                  />
                </a-form-item>
                <a-form-item label="Số điện thoại">
                  <a-input
                    v-decorator="[
                      'phone_number',
                      {
                        initialValue: user.phone_number,
                        rules: [{ max: 12, message: 'Tối đa 12 ký tự' }],
                      },
                    ]"
                    placeholder="Nhập số điện thoại"
                    :disabled="disabled"
                  />
                </a-form-item>
                <a-form-item label="Địa chỉ">
                  <a-textarea
                    v-decorator="[
                      'address',
                      {
                        initialValue: user.address,
                      },
                    ]"
                    placeholder="Nhập địa chỉ"
                    :autosize="{ minRows: 2, maxRows: 3 }"
                    :disabled="disabled"
                  />
                </a-form-item>
                <a-row type="flex" justify="space-between" align="middle">
                  <a-col>
                    <a-button icon="lock" @click="visible = true">Đổi mật khẩu</a-button>
                  </a-col>
                  <a-col>
                    <a-button v-if="!disabled" type="primary" htmlType="submit" icon="save">
                      Lưu
                    </a-button>
                    <a-button @click="disabled = !disabled" :icon="disabled ? 'edit' : ''">
                      {{ disabled ? "Cập nhật" : "Hủy" }}
                    </a-button>
                  </a-col>
                </a-row>
              </a-form>

              <change-password-form
                ref="changePasswordForm"
                :visible="visible"
                :confirmLoading="confirmLoading"
                @cancel="handleCancel"
                @ok="handleOk"
              />
            </a-col>
          </a-row>
        </a-col>
      </a-row>
    </a-card>
  </div>
</template>

<script>
  const ChangePasswordForm = {
    props: ["visible", "confirmLoading"],
    beforeCreate() {
      this.changePasswordForm = this.$form.createForm(this, { name: "form_change_password" });
    },
    methods: {
      handleConfirmBlur(e) {
        const value = e.target.value;
        this.confirmPassword = this.confirmPassword || !!value;
      },
      compareToFirstPassword(rule, value, callback) {
        const form = this.changePasswordForm;
        if (value && value !== form.getFieldValue("newPassword")) {
          callback("Mật khẩu nhập lại không đúng");
        } else {
          callback();
        }
      },
      validateToNextPassword(rule, value, callback) {
        const form = this.changePasswordForm;
        if (value && this.confirmPassword) {
          form.validateFields(["confirmNewPassword"], { force: true });
        }
        callback();
      },
    },
    template: `
    <a-modal
      :width="450"
      :visible="visible"
      :confirmLoading="confirmLoading"
      centered
      title='Thay đổi mật khẩu'
      okText='Lưu'
      cancelText='Hủy'
      @cancel="() => { $emit('cancel') }"
      @ok="() => { $emit('ok') }"
    >
      <a-form layout='vertical' :form="changePasswordForm" hideRequiredMark>
        <a-form-item label='Mật khẩu cũ' hasFeedback>
          <a-input
            v-decorator="[
              'currentPassword',
              {
                rules: [
                  { required: true, message: 'Không được trống' },
                  {
                    whitespace: true,
                    message: 'Không được nhập khoảng trắng'
                  },
                  { min: 6, message: 'Tối thiểu 6 ký tự' },
                  { max: 255, message: 'Tối đa 255 ký tự' }
                ]
              }
            ]"
            type="password"
            placeholder="Nhập mật khẩu cũ"
          />
        </a-form-item>
        <a-form-item label='Mật khẩu mới' hasFeedback>
          <a-input
            v-decorator="[
              'newPassword',
              {
                rules: [
                  { required: true, message: 'Không được trống' },
                  {
                    whitespace: true,
                    message: 'Không được nhập khoảng trắng'
                  },
                  { min: 6, message: 'Tối thiểu 6 ký tự' },
                  { max: 255, message: 'Tối đa 255 ký tự' },
                  {
                    validator: validateToNextPassword,
                  },
                ]
              }
            ]"
            type="password"
            placeholder="Nhập mật khẩu mới"
          />
        </a-form-item>
        <a-form-item label='Xác nhận mật khẩu mới' hasFeedback>
          <a-input
            v-decorator="[
              'confirmNewPassword',
              {
                rules: [
                  { required: true, message: 'Không được trống' },
                  {
                    validator: compareToFirstPassword,
                  },
                ]
              }
            ]"
            type="password"
            placeholder="Xác nhận mật khẩu mới"
            @blur="handleConfirmBlur"
          />
        </a-form-item>
      </a-form>
    </a-modal>
  `,
  };
  import { mapGetters, mapActions } from "vuex";
  import { uploadImage } from "@/services/upload";
  export default {
    components: { ChangePasswordForm },
    metaInfo: {
      title: "Thông tin cá nhân",
    },
    data() {
      return {
        disabled: true,
        visible: false,
        confirmLoading: false,
        formItemLayout: {
          labelCol: {
            xs: { span: 24 },
            sm: { span: 6 },
          },
          wrapperCol: {
            xs: { span: 24 },
            sm: { span: 18 },
          },
        },
      };
    },
    computed: {
      ...mapGetters("profile", ["user", "loading"]),
    },
    created() {
      this.formUserData = this.$form.createForm(this, { name: "form_profile" });
      this.getUser();
    },
    mounted() {},
    methods: {
      ...mapActions("profile", ["getUser", "updateDetails", "updatePassword", "updateAvatar"]),
      //Change user data
      saveForm() {
        this.formUserData.validateFields((err, values) => {
          if (!err) {
            try {
              this.updateDetails(values);
            } finally {
              this.disabled = true;
            }
          }
        });
      },
      //Change password
      handleCancel() {
        this.visible = false;
      },
      handleOk() {
        const form = this.$refs.changePasswordForm.changePasswordForm;
        form.validateFields(async (err, values) => {
          if (!err) {
            try {
              this.confirmLoading = true;
              await this.updatePassword(values);
              this.handleCancel();
            } catch (error) {
            } finally {
              form.resetFields();
              this.confirmLoading = false;
            }
          }
        });
      },
      //Change avatar
      onChangeAvatar(e) {
        const image = e.target.files[0];
        uploadImage("users", image).then((url) => {
          this.updateAvatar({ avatar: url });
        });
      },
    },
  };
</script>

<style lang="less" scoped>
  .title {
    text-align: center;
    font-weight: 500;
    text-transform: uppercase;
  }
  .container-avatar {
    border-radius: 50%;
    background: #fff;
    border: 1px solid rgba(0, 0, 0, 0.15);
    height: auto;
    cursor: pointer;
    img {
      object-fit: contain;
      object-position: center;
      padding: 4px;
      width: 100%;
      border-radius: 50%;
      overflow: hidden;
    }
  }
</style>
