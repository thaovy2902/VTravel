import Vue from "vue";

import md from "marked";

import ImageCropper from "./components/global/ImageCropper";
import LoadingFullScreen from "./components/global/LoadingFullScreen";
import ReCaptcha from "./components/global/ReCaptcha";

Vue.component("ImageCropper", ImageCropper);
Vue.component("LoadingFullScreen", LoadingFullScreen);
Vue.component("ReCaptcha", ReCaptcha);

window.md = md;
window.eventBus = new Vue();
