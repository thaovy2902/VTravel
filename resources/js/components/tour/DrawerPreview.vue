<template>
  <a-drawer :width="width" :visible="visiblePreview" @close="close">
    <p class="preview-title">Tour #{{ data.code }}</p>
    <p :style="pStyle">Tour's Detail</p>
    <a-row>
      <a-col :span="12">
        <img :style="{ height: '95%', width: '95%', borderRadius: '3px', boxShadow: '2px 2px 6px 0 #fff24' }" :src="data.image" />
      </a-col>
      <a-col :span="12">
        <a-row>
          <a-col>
            <description-item title="Tour Name" :content="data.name" />
          </a-col>
          <a-col>
            <description-item title="Category" :content="data.category_name" />
          </a-col>
          <a-col>
            <description-item title="Departure" :content="data.depart" />
          </a-col>
          <a-col>
            <description-item title="Origin" :content="data.from_place_name" />
          </a-col>
          <a-col>
            <description-item title="Destination" :content="data.to_place_name" />
          </a-col>
        </a-row>
      </a-col>
    </a-row>
    <a-row>
      <a-col :span="12">
        <description-item title="Time" :content="`${data.number_days} days`" />
      </a-col>
      <a-col :span="12">
        <description-item title="Maximum Participants" :content="`${data.number_persons} People`" />
      </a-col>
    </a-row>
    <a-row>
      <a-col :span="12">
        <description-item title="Transport" :content="data.transport" />
      </a-col>
      <a-col :span="12">
        <description-item title="Adults" :content="`${data.price_default} $`" />
      </a-col>
    </a-row>
    <a-row>
      <a-col :span="12">
        <description-item title="Children (2-12)" :content="`${data.price_children} $`" />
      </a-col>
      <a-col :span="12">
        <description-item title="Infants (0-2)" :content="`${data.price_baby} $`" />
      </a-col>
    </a-row>
    <a-row>
      <a-col :span="12">
        <description-item title="Is Attraction" :content="data.is_featured ? 'Yes' : 'No'" />
      </a-col>
      <a-col :span="12">
        <description-item title="Hide/Show" :content="data.is_active ? 'Show' : 'Ide'" />
      </a-col>
    </a-row>

    <a-divider />

    <p :style="pStyle">Description</p>
    <a-row>
      <a-col :span="24">
        <div v-html="description"></div>
      </a-col>
    </a-row>

    <a-divider />

    <a-row>
      <a-col :span="24">
        <description-item title="Note" :content="data.note" />
      </a-col>
    </a-row>
  </a-drawer>
</template>

<script>
  import DescriptionItem from "@/components/description/DescriptionItem";
  import { colorActive } from "@/helpers/tools";
  export default {
    components: { DescriptionItem },
    props: {
      visiblePreview: {
        type: Boolean,
        default: false
      },
      width: {
        type: Number,
        default: 640
      },
      data: {
        type: Object,
        default: {}
      }
    },
    data() {
      return {
        pStyle: {
          fontSize: "18px",
          color: "#111111",
          lineHeight: "24px",
          fontWeight: "500",
          display: "block",
          marginBottom: "16px"
        }
      };
    },
    computed: {
      description() {
        return md.parse(this.data.description);
      }
    },
    methods: {
      close() {
        this.$emit("closeDrawerPreview");
      },
      colorActive(v) {
        return colorActive(v);
      }
    }
  };
</script>

<style lang="less">
  .preview-title {
    font-size: 16px;
    font-weight: 700;
    color: #ff8f00;
    line-height: 24px;
    display: block;
    margin-bottom: 16px;
  }
</style>
