<template>
  <a-modal
    :visible="visible"
    :confirmLoading="confirmLoading"
    centered
    title="Cancel"
    cancelText="Cancel"
    okText="Xác nhận"
    @cancel="$emit('cancel')"
    @ok="$emit('ok')"
  >
    <a-form layout="vertical" :form="form" hideRequiredMark>
      <a-form-item label="Cancellation reason">
        <a-input
          type="textarea"
          v-decorator="[
            'reason_cancel',
            {
              rules: [
                { required: true, message: 'This field is required' },
                { min: 10, message: 'Must be at least 10 characters long' }
              ]
            }
          ]"
          placeholder="Input cancellation reason"
        />
      </a-form-item>
    </a-form>
  </a-modal>
</template>

<script>
  export default {
    props: ["visible", "confirmLoading"],
    beforeCreate() {
      this.form = this.$form.createForm(this, { name: "form_cancel_order" });
    }
  };
</script>

<style></style>
