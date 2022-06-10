export const callerApi = (endpoint, method = "GET", data = {}, responseType = "json") => {
  return axios({
    method: method,
    url: endpoint,
    data: data,
    responseType: responseType,
    headers: {
      Authorization: `Bearer ${localStorage.getItem("auth__token")}`,
    },
  });
};
