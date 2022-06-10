<template>
  <a-dropdown placement="bottomRight" :trigger="['click']">
    <div :style="{ margin: '0 24px', cursor: 'pointer' }">
      <a-badge :count="unReadCount">
        <a-icon type="bell" :style="{ fontSize: '18px' }" />
      </a-badge>
    </div>

    <template slot="overlay">
      <div class="notification">
        <div class="header">
          <span>Thông báo</span>
          <div class="action">
            <div v-if="unRead.length > 0" :style="{ marginRight: '12px' }">
              <a-tooltip placement="bottom" title="Đánh giấu là đã đọc">
                <span style="cursor: pointer;" @click="markAsReadAll">
                  <a-icon type="check-circle" :style="{ fontSize: '18px', lineHeight: '18px' }" />
                </span>
              </a-tooltip>
            </div>
            <div v-if="unRead.length !== 0 || read.length !== 0">
              <a-tooltip placement="bottom" title="Xóa tất cả">
                <span style="cursor: pointer;" @click="deleteNotification">
                  <a-icon type="close-circle" :style="{ fontSize: '18px', lineHeight: '18px' }" />
                </span>
              </a-tooltip>
            </div>
          </div>
        </div>
        <div v-if="unRead.length === 0 && read.length === 0">
          Chưa có thông báo nào
        </div>
        <a-list v-if="unRead.length > 0" itemLayout="horizontal" :dataSource="unRead">
          <a-list-item slot="renderItem" slot-scope="item" class="px-6">
            <div class="item unread" @click="readIt(item.id)">
              <div>
                <div>{{ item.orderer }} đã đặt tour {{ item.tour }}</div>
              </div>
            </div>
          </a-list-item>
        </a-list>
        <a-list v-if="read.length > 0" itemLayout="horizontal" :dataSource="read">
          <a-list-item slot="renderItem" slot-scope="item" class="px-6">
            <div class="item">
              <div>
                <div>{{ item.orderer }} đã đặt tour {{ item.tour }}</div>
              </div>
            </div>
          </a-list-item>
        </a-list>
      </div>
    </template>
  </a-dropdown>
</template>

<script>
  import { vp } from "@/helpers/tools";
  import { mapActions, mapGetters } from "vuex";
  export default {
    computed: {
      ...mapGetters("notification", ["read", "unRead", "unReadCount"])
    },
    created() {
      this.fetchNotification();
      this.listen();
    },
    methods: {
      ...mapActions("notification", [
        "fetchNotification",
        "readNotification",
        "markAsReadAll",
        "deleteNotification",
        "broadcastNotification"
      ]),
      readIt(notificationId) {
        this.readNotification({ id: notificationId });
      },
      listen() {
        if (!this.$auth.user && !this.$auth.loggedIn) {
          return;
        }
        if (window.Echo) {
          Echo.private("App.Models.User." + this.$auth.user.id).notification(notification => {
            this.broadcastNotification(notification);
          });
        }
      }
    }
  };
</script>

<style lang="less" scoped>
  .notification {
    background: white;
    width: 320px;
    max-height: calc(100vh - 64px - 12px - 16px);
    overflow: auto;
    padding: 12px;
    border-radius: 8px;
    box-shadow: 0 1px 10px 0 rgba(0, 0, 0, 0.2);
    .header {
      display: flex;
      flex-direction: row;
      align-items: center;
      justify-content: space-between;
      margin-bottom: 12px;
      span {
        font-size: 15px;
        line-height: 15px;
        font-weight: 600;
      }
    }
    .action {
      display: flex;
      flex-direction: row;
      align-items: center;
      justify-content: flex-start;
    }
    .unread {
      font-weight: 600;
    }
    .px-6 {
      padding-left: 6px;
      padding-right: 6px;
    }
    .item {
      display: flex;
      flex-direction: row;
      align-items: center;
      justify-content: flex-start;
      cursor: pointer;
    }
  }
</style>
