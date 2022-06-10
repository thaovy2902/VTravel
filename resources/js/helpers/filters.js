import Vue from "vue";

const dateTimeFilter = (value) => {
  const date = new Date(value);
  return date.toLocaleString(["vi-VI"], {
    hour: "2-digit",
    minute: "2-digit",
    month: "2-digit",
    day: "2-digit",
    year: "numeric",
  });
};

const dateFilter = (value) => {
  const date = new Date(value);
  return date.toLocaleString(["vi-VI"], {
    month: "2-digit",
    day: "2-digit",
    year: "numeric",
  });
};

const statusFilter = (value) => {
  return value ? "ACTIVATE" : "INACTIVATE";
};

const statusOrderFilter = (value) => {
  let result;
  switch (value) {
    case (value = 1):
      result = "PENDING";
      break;
    case (value = 2):
      result = "UNPAID";
      break;
    case (value = 3):
      result = "SUCCESSFUL";
      break;
    case (value = 4):
      result = "CANCELED";
      break;
  }

  return result;
};

const typeFeedbackFilter = (type) => {
  let result;
  switch (type) {
    case (type = 1):
      result = "Rất không hài lòng";
      break;
    case (type = 2):
      result = "Không hài lòng";
      break;
    case (type = 3):
      result = "Bình thường";
      break;
    case (type = 4):
      result = "Hài lòng";
      break;
    case (type = 5):
      result = "Tuyệt vời";
      break;
  }

  return result;
};

const upperCaseFilter = (value) => {
  return value.toUpperCase();
};

const currencyVN = (value) => {
  const valueStr = value + "";
  return `${valueStr.replace(/\B(?=(\d{3})+(?!\d))/g, ".")} VND`;
};

const departFilter = (value) => {
  if (value === "daily") {
    return "Hằng ngày";
  }
  if (value === "contact") {
    return "Liên hệ";
  }
};

const numberDayFilter = (value) => {
  return `${value} ngày`;
};

const numberPersonFilter = (value) => {
  return `${value} người`;
};

const percentFilter = (value) => {
  return `${value} %`;
};

const roundedAvgRating = (value) => {
  return Math.round(value * 10) / 10;
};

Vue.filter("dateTime", dateTimeFilter);
Vue.filter("date", dateFilter);
Vue.filter("status", statusFilter);
Vue.filter("statusOrder", statusOrderFilter);
Vue.filter("typeFeedback", typeFeedbackFilter);
Vue.filter("upperCase", upperCaseFilter);
Vue.filter("currencyVN", currencyVN);
Vue.filter("depart", departFilter);
Vue.filter("numberDay", numberDayFilter);
Vue.filter("numberPerson", numberPersonFilter);
Vue.filter("percent", percentFilter);
Vue.filter("rounded", roundedAvgRating);
