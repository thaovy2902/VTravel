<template>
  <a-card class="shadow-sm" title="Reviews" :bordered="false" :bodyStyle="{ padding: '16px' }" style="margin:16px 0">
    <div v-if="avgRating.count > 0" slot="extra">
      <span class="avg-rating">{{ avgRating.avg | rounded }} /5</span>
      <a-icon class="avg-icon" theme="filled" type="star" />
      <a-divider type="vertical" />
      <span class="count-rating">{{ avgRating.count }} Reviews</span>
    </div>

    <div v-if="$auth.loggedIn && $auth.user">
      <div v-if="isRated" class="alert">""</div>
      <a-button v-if="!isVisible" type="dashed" :icon="!isRated ? 'plus' : 'edit'" block @click="!isRated ? add() : edit()">
        {{ !isRated ? "Write Your Review" : "Edit Your Review" }}
      </a-button>
      <a-comment v-else>
        <a-avatar v-if="$auth.user.avatar" slot="avatar" :src="$auth.user.avatar" />
        <a-avatar v-else slot="avatar" icon="user" />
        <div slot="content">
          <a-form-item>
            <a-textarea placeholder="Content" :rows="3" :value="rating.content" @change="onChangeContent"></a-textarea>
          </a-form-item>
          <a-row type="flex" justify="space-between" align="middle">
            <a-col>
              <a-rate :tooltips="descRating" :value="rating.scores" allowClear @change="onChangeRating" />
            </a-col>
            <a-col>
              <a-button @click="close" style="margin-right:6px">Cancel</a-button>
              <a-button type="primary" :loading="loadingRating" :disabled="rating.scores === 0 || !rating.content" @click="onSubmitRating">
                {{ !isEdit ? "Submit" : "Edit Your Review" }}
              </a-button>
            </a-col>
          </a-row>
        </div>
      </a-comment>
    </div>
    <div v-if="!$auth.loggedIn && !$auth.user" style="text-align:center">
      <router-link :to="{ name: 'login' }">Log in</router-link> to write your review.
    </div>

    <a-list class="comment-list" itemLayout="horizontal" :dataSource="ratings">
      <a-list-item slot="renderItem" slot-scope="item">
        <a-comment :author="item.author">
          <div slot="avatar">
            <a-avatar v-if="item.avatar" :src="item.avatar" :size="32" shape="circle" />
            <a-avatar v-else icon="user" :size="32" />
          </div>
          <p slot="content"><a-rate :value="item.scores" disabled />{{ item.content }}</p>
          <a-tooltip slot="datetime" :title="item.created_at">
            <span>{{ item.created_at }}</span>
          </a-tooltip>

          <template slot="actions" v-if="item.is_author">
            <span key="edit-rating" @click="edit()">Edit</span>
            <a-popconfirm title="Bạn có chắc chắn?" okText="Có" cancelText="Không" @confirm="onDeleteRating(item.id)">
              <span key="delete-rating">Delete</span>
            </a-popconfirm>
          </template>
        </a-comment>
      </a-list-item>
    </a-list>

    <div v-if="ratings.length > 0" style="text-align:center">
      <a-pagination
        size="small"
        :total="pagination.total"
        :pageSize="pagination.perPage"
        :current="pagination.current"
        :showTotal="(total, range) => `${range[0]}-${range[1]} of ${total} items`"
        @change="onChangePageRating"
      />
    </div>
  </a-card>
</template>

<script>
  import { mapGetters, mapActions, mapState } from "vuex";
  export default {
    props: {
      tour: {
        type: Object,
      },
    },
    data() {
      return {
        descRating: ["Terrible", "Bad", "Normal", "Good", "Excellent"],
        rating: {
          scores: 0,
          content: "",
        },
        pagination: {
          total: 0,
          perPage: 5,
          current: 1,
        },
        ratingId: "",
        isRated: this.tour.is_rating,
        loadingRating: false,
        isVisible: false,
        isEdit: false,
      };
    },
    computed: {
      ...mapState("tourDetail", ["avgRating"]),
      ...mapGetters("tourDetail", ["ratings", "getRating"]),
    },
    mounted() {
      this.fetchDataRating();
      this.getAvgRating();
    },
    methods: {
      ...mapActions("tourDetail", ["fetchRatings", "sendRating", "updateRating", "deleteRating", "getAvgRating"]),
      async fetchDataRating(params = {}) {
        const pagination = { ...this.pagination };
        const { meta } = await this.fetchRatings(params);
        pagination.total = meta.total;
        pagination.current = meta.current_page;
        pagination.perPage = meta.per_page;
        this.pagination = pagination;
      },
      onChangePageRating(page) {
        this.fetchDataRating({ page });
      },
      onChangeRating(value) {
        this.rating.scores = value;
      },
      onChangeContent(e) {
        this.rating.content = e.target.value;
      },
      add() {
        this.isVisible = true;
        this.isEdit = false;
      },
      edit() {
        this.isVisible = true;
        this.isEdit = true;
        this.ratingId = this.getRating.id;
        this.rating.content = this.getRating.content;
        this.rating.scores = this.getRating.scores;
      },
      close() {
        this.isVisible = false;
        this.isEdit = false;
        this.rating.scores = 0;
        this.rating.content = "";
      },
      async onSubmitRating() {
        this.loadingRating = true;
        if (!this.isEdit) {
          await this.sendRating(this.rating);
          this.isRated = true;
        } else {
          await this.updateRating({ id: this.ratingId, values: this.rating });
        }
        this.loadingRating = false;
        this.close();
      },
      async onDeleteRating(ratingId) {
        await this.deleteRating(ratingId);
        this.isRated = false;
      },
    },
  };
</script>

<style>
  .alert {
    text-align: center;
    font-weight: 500;
    margin-bottom: 12px;
  }
  .avg-rating {
    font-size: 15px;
    line-height: 15px;
    font-weight: 700;
  }
  .avg-icon {
    margin-left: 2px;
    font-size: 14px;
    color: #fadb14;
  }
  .count-rating {
    font-size: 13px;
    line-height: 13px;
    font-style: italic;
  }
  .ant-rate {
    font-size: 16px;
  }
</style>
