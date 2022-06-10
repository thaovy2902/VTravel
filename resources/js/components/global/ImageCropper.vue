<template>
  <a-modal title="Cắt ảnh" :width="600" :visible="visibleModal" @cancel="modalCancel" @ok="modalOk">
    <div :style="{ width: '552px', height: '300px' }">
      <VueCropper ref="cropper" v-bind="options" />
    </div>
    <template slot="footer">
      <a-button key="back" @click="modalCancel">Hủy</a-button>
      <a-button key="submit" type="primary" @click="modalOk">
        Lưu
      </a-button>
    </template>
  </a-modal>
</template>

<script>
  import { VueCropper } from "vue-cropper";
  export default {
    components: { VueCropper },
    props: {
      imageUrl: {
        type: String,
        default: null,
      },
      visibleModal: {
        type: Boolean,
        default: false,
      },
      fixedNumber: {
        type: Array,
        default() {
          return [20, 9];
        },
      },
    },
    data() {
      return {
        options: {
          img: null,
          full: true,
          canMove: true,
          canScale: true,
          autoCropWidth: 320,
          autoCrop: true,
          fixedNumber: this.fixedNumber,
          fixed: true,
          outputSize: 0.8,
          outputType: "webp",
        },
      };
    },
    beforeUpdate() {
      this.options.img = this.imageUrl;
    },
    methods: {
      modalCancel() {
        this.options.img = null;
        this.$emit("onCloseModal", false);
      },
      modalOk() {
        this.$refs.cropper.getCropBlob((data) => {
          this.$emit("getImage", data);
          this.modalCancel();
        });
      },
    },
  };
</script>

<style></style>
