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
      result = "Terrible";
      break;
    case (type = 2):
      result = "Bad";
      break;
    case (type = 3):
      result = "Normal";
      break;
    case (type = 4):
      result = "Good";
      break;
    case (type = 5):
      result = "Excellent";
      break;
  }

  return result;
};

const upperCaseFilter = (value) => {
  return value.toUpperCase();
};

const currencyVN = (value) => {
  const valueStr = value + "";
  return `${valueStr.replace(/\B(?=(\d{3})+(?!\d))/g, ",")} $`;
};

const departFilter = (value) => {
  if (value === "daily") {
    return "Daily";
  }
  if (value === "contact") {
    return "Contact";
  }
};

const numberDayFilter = (value) => {
  return `${value} days`;
};

const numberPersonFilter = (value) => {
  return `${value} people`;
};

const percentFilter = (value) => {
  return `${value} %`;
};

const roundedAvgRating = (value) => {
  return Math.round(value * 10) / 10;
};

Vue.filter("dateTime", dateTimeFilter);
Vue.filter("date", dateFilter);
Vue.filter("status", StatusFilter);
Vue.filter("statusOrder", StatusOrderFilter);
Vue.filter("typeFeedback", typeFeedbackFilter);
Vue.filter("upperCase", upperCaseFilter);
Vue.filter("currencyVN", currencyVN);
Vue.filter("depart", departFilter);
Vue.filter("numberDay", numberDayFilter);
Vue.filter("numberPerson", numberPersonFilter);
Vue.filter("percent", percentFilter);
Vue.filter("rounded", roundedAvgRating);
