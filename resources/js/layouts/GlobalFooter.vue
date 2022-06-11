<template>
  <a-layout-footer id="global-footer">
    <div class="global-footer-top">
      <a-row type="flex" justify="center" align="top">
        <a-col :xs="24" :sm="24" :md="8" :lg="8">
          <div class="footer-item">
            <div class="footer-item-title">VTravel</div>
            <div>
              <div class="logo-footer">
                <img src="/img/logo.svg" alt="logo-footer" />
              </div>
              <div style="fontSize:16px">
                Hệ thống đặt tour trực tuyến hàng đầu Việt Nam
              </div>
            </div>
          </div>
        </a-col>
        <a-col :xs="24" :sm="24" :md="8" :lg="8">
          <div class="footer-item">
            <div class="footer-item-title">Contact</div>
            <div style="fontSize:16px">
              <div class="mb-1"><a-icon type="home" style="marginRight:8px" />Đà Nẵng, Việt Nam</div>
              <div class="mb-1"><a-icon type="phone" style="marginRight:8px" />+84 853 753 422</div>
              <div class="mb-1"><a-icon type="contacts" style="marginRight:8px" />+84 853 753 422</div>
              <div class="mb-1"><a-icon type="mail" style="marginRight:8px" />info@vtravel.com</div>
            </div>
          </div>
        </a-col>
        <a-col :xs="24" :sm="24" :md="8" :lg="8">
          <div class="footer-item">
            <div class="footer-item-title">Theo dõi chúng tôi</div>
            <div>
              <a href="#" target="_blank" class="link-facebook">
                <a-icon theme="filled" type="facebook" class="icon-social" />
              </a>
              <a href="#" target="_blank" class="link-instagram">
                <a-icon theme="filled" type="instagram" class="icon-social" />
              </a>
              <a href="#" target="_blank" class="link-youtube">
                <a-icon theme="filled" type="youtube" class="icon-social" />
              </a>
            </div>
          </div>
        </a-col>
      </a-row>
    </div>
    <div class="global-footer-bottom">
      Made with
      <a-icon type="heart" theme="filled" style="color:#bf0b2c" /> by VTravel TEAM ©{{ getCurrentYear() }}
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
    color: #fff;
    box-shadow: 0px -1px 10px 0 rgba(0, 0, 0, 0.2);
    .global-footer-top {
      background: #002140;
      padding: 24px 50px;
      .footer-item {
        .logo-footer {
          background: #fff;
          border-radius: 2px;
          width: fit-content;
          margin-bottom: 6px;
          img {
            padding: 4px 6px;
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
      background: #001529;
      padding: 12px 0;
      text-align: center;
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
