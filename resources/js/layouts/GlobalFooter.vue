<template>
  <a-layout-footer id="global-footer">
    <div class="global-footer-top">
      <a-row type="flex" justify="space-between">
        <a-col :xs="24" :sm="24" :md="12" :lg="12">
          <div class="footer-item">
            <div>
              <div class="logo-footer">
                <router-link :to="{ name: 'home' }">
                  <img class="logo" src="/img/logo.svg" alt="logo" />
                </router-link>
              </div>
              <div style="fontSize: 20px">
                A Tripadvisor Company
              </div>
            </div>
          </div>
        </a-col>
        <a-col :xs="24" :sm="24" :md="6" :lg="6">
          <div class="footer-item">
            <div class="footer-item-title">Menu</div>
            <div style="fontSize:16px">
              <div class="mb-1"><router-link :to="{ name: 'home' }">Home</router-link></div>
              <div class="mb-1"><router-link :to="{ name: 'tours' }">Tours</router-link></div>
            </div>
          </div>
        </a-col>
        <a-col :xs="24" :sm="24" :md="6" :lg="6">
          <div class="footer-item">
            <div class="footer-item-title">Contact</div>
            <div style="fontSize:16px">
              <div class="mb-1"><a-icon type="home" style="marginRight:8px" />Da Nang, Viet Nam</div>
              <div class="mb-1"><a-icon type="phone" style="marginRight:8px" />+84 853 753 422</div>
              <div class="mb-1"><a-icon type="contacts" style="marginRight:8px" />+84 853 753 422</div>
              <div class="mb-1"><a-icon type="mail" style="marginRight:8px" />info@vtravel.com</div>
            </div>
          </div>
        </a-col>
      </a-row>
    </div>
    <div class="global-footer-bottom">
      © VTravel, Inc. 2022.
    </div>
  </a-layout-footer>
</template>

<script>
  export default {
    methods: {
      getCurrentYear() {
        return new Date().getFullYear();
      },
    },
  };
</script>

<style lang="less" scoped>
  #global-footer {
    padding: 0;
    color: #111111;
    a {
      color: #111111;
    }
    .global-footer-top {
      background: #fbfbfb;
      padding: 24px 120px;
      .footer-item {
        .logo-footer {
          background: #fff;
          border-radius: 2px;
          width: fit-content;
          margin-bottom: 6px;
          img {
            padding: 4px 6px;
            background: #fbfbfb;
          }
        }
        .footer-item-title {
          font-size: 15px;
          text-transform: uppercase;
          font-weight: 500;
          margin-bottom: 16px;
        }
      }
    }
    .global-footer-bottom {
      background: #fbfbfb;
      padding: 12px 120px;
      text-align: left;
      color: rgba(17, 17, 17, 0.5);
    }
    .icon-social {
      font-size: 28px;
      background: #fff;
      padding: 2px;
      border-radius: 4px;
    }
    .link-facebook {
      color: #3b5999;
      transition: 0.3s;
      &:hover {
        opacity: 0.9;
        border-radius: 4px;
      }
    }
    .link-instagram {
      color: #ff5700;
      margin: 0 12px;
      transition: 0.3s;
      &:hover {
        opacity: 0.9;
        border-radius: 4px;
      }
    }
    .link-youtube {
      color: #cd201f;
      transition: 0.3s;
      &:hover {
        opacity: 0.9;
        border-radius: 4px;
      }
    }
  }
</style>
