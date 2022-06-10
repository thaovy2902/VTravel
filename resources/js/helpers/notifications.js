import { vp } from "./tools";

const notification = vp.$notification;

notification.config({
  placement: "bottomLeft"
});

function toast({ type, message, description, duration }) {
  return notification[type]({ message, description, duration });
}

const options = {
  success: toast,
  error: toast,
  info: toast,
  warning: toast
};

const $notify = {};

for (let type in options) {
  $notify[type] = (message, description, { duration = 4 } = {}) => {
    toast({ type, message, description, duration });
  };
}

const $destroys = () => notification.destroy();

vp.$destroynotify = $destroys;

vp.$notify = $notify;
