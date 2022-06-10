<template>
  <ckeditor :editor="editor" :config="editorConfig" v-model="editorData" tag-name="textarea" />
</template>

<script>
  import ClassicEditor from "@ckeditor/ckeditor5-build-classic";

  export default {
    data() {
      return {
        editor: ClassicEditor,
        editorData: "",
        editorConfig: {
          toolbar: [
            "heading",
            "|",
            "bold",
            "italic",
            "link",
            "|",
            "outdent",
            "indent",
            "|",
            "bulletedList",
            "numberedList",
            "|",
            "blockQuote",
            "mediaEmbed",
            "|",
            "undo",
            "redo"
          ]
        }
      };
    },
    watch: {
      editorData(data) {
        this.$emit("sendEditorData", data);
      }
    },
    beforeCreate() {
      eventBus.$on("emptyEditor", e => {
        this.editorData = "";
      });
      eventBus.$on("receiverDataEditor", e => {
        this.editorData = e;
      });
    },
    beforeDestroy() {
      eventBus.$off("emptyEditor");
      eventBus.$off("receiverDataEditor");
    }
  };
</script>

<style></style>
