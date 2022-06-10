window.axios = require("axios");
const API_URL = `${process.env.MIX_APP_URL}/api/v1`;
let jwtToken = window.localStorage.getItem("auth__token");

window.axios.defaults.baseURL = API_URL;
window.axios.defaults.headers.common["X-Requested-With"] = "XMLHttpRequest";
window.axios.defaults.headers.common["Accept"] = "application/json";
window.axios.defaults.headers.common["Content-Type"] = "application/json";

let token = document.head.querySelector('meta[name="csrf-token"]');

if (token) {
  window.axios.defaults.headers.common["X-CSRF-TOKEN"] = token.content;
} else {
  console.error("CSRF token not found: https://laravel.com/docs/csrf#csrf-x-csrf-token");
}

import Echo from "laravel-echo";

window.Pusher = require("pusher-js");

if (jwtToken) {
  window.Echo = new Echo({
    broadcaster: "pusher",
    key: document.head.querySelector('meta[name="pusher-key"]').content,
    authEndpoint: "/api/broadcasting/auth",
    cluster: "ap1",
    forceTLS: true,
    encrypted: true,
    auth: {
      headers: {
        Authorization: `Bearer ${jwtToken}`,
      },
    },
  });
}
