import Vue from "vue";
import VueProgressBar from "vue-progressbar";

const config = {
  color: "#FF8F00",
  failedColor: "#EA4335",
  thickness: "3px",
  transition: {
    speed: "0.2s",
    opacity: "0.6s",
    termination: 300
  }
};

Vue.use(VueProgressBar, config);
