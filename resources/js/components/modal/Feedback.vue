<template>
  <form-feedback ref="feedbackForm" :visible="visible" :confirmLoading="confirmLoading" @cancel="handleCancel" @send="handleSend" />
</template>

<script>
  const FormFeedback = {
    props: ["visible", "confirmLoading"],
    data() {
      return {
        desc: ["Rất không hài lòng", "Không hài lòng", "Bình thường", "Hài lòng", "Tuyệt vời"]
      };
    },
    beforeCreate() {
      this.form = this.$form.createForm(this, { name: "form_feedback" });
    },
    template: `
      <a-modal
        :visible="visible"
        :confirmLoading="confirmLoading"
        centered
        title='Góp ý website'
        cancelText="Đóng"
        okText='Gửi'
        @cancel="() => { $emit('cancel') }"
        @ok="() => { $emit('send') }"
      >
      <a-form layout="vertical" :form="form" hideRequiredMark>
        <a-row :gutter="16">
          <a-col :span="12">
            <a-form-item label="Họ tên">
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
                placeholder="Nhập họ tên"
              />
            </a-form-item>
          </a-col>
          <a-col :span="12">
            <a-form-item class="mb-0" label="Bạn cảm thấy thế nào?">
              <a-rate :tooltips="desc" v-decorator="['type']" style="fontSize: 24px">
                <a-icon slot="character" type="smile" />
              </a-rate>
            </a-form-item>
          </a-col>
        </a-row>
        <a-row :gutter="16">
          <a-col :span="12">
            <a-form-item label="Email">
              <a-input
                v-decorator="[
                  'email',
                  {
                    rules: [
                      { required: true, message: 'Không được trống' },
                      { type: 'email', message: 'Không đúng định dạng email' }
                    ]
                  }
                ]"
                placeholder="Nhập email"
              />
            </a-form-item>
          </a-col>
          <a-col :span="12">
            <a-form-item label="Số điện thoại">
              <a-input v-decorator="['phone_number']" placeholder="Nhập số điện thoại" />
            </a-form-item>
          </a-col>
        </a-row>
        <a-form-item label="Nội dung">
          <a-textarea
            v-decorator="[
              'content',
              { rules: [
                { required: true, message: 'Không được trống' },
                { min: 10, message: 'Ít nhất 10 ký tự' },
              ] }
            ]"
            placeholder="Nhập nội dung":rows="5" />
        </a-form-item>
      </a-form>
    </a-modal>
    `
  };
  export default {
    components: { FormFeedback },
    props: {
      visible: {
        type: Boolean,
        default: false
      }
    },
    data() {
      return {
        confirmLoading: false
      };
    },
    methods: {
      handleCancel() {
        this.$emit("close");
      },
      handleSend() {
        const form = this.$refs.feedbackForm.form;
        form.validateFields(async (err, values) => {
          if (!err) {
            try {
              this.confirmLoading = true;
              const { data, status } = await axios.post("send-feedback", values);
              if (data && status === 201) {
                this.$message.success(data.message);
                form.resetFields();
                this.handleCancel();
              }
            } catch ({ response }) {
              if (response && response.status === 422) {
                const message = Object.values(response.data.message)[0];
                this.$message.error(message);
              }
            } finally {
              this.confirmLoading = false;
            }
          }
        });
      }
    }
  };
</script>

<style></style>
