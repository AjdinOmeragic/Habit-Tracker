let RestClient = {
  get: function (url, callback, error_callback) {
    console.log("GET request to:", Constants.PROJECT_BASE_URL + url);

    $.ajax({
      url: Constants.PROJECT_BASE_URL + url,
      type: "GET",
      beforeSend: function (xhr) {
        let token = localStorage.getItem("user_token");
        console.log("Token for request:", token ? "Present" : "Missing");
        if (token) {
          xhr.setRequestHeader("Authorization", "Bearer " + token);
          console.log("Authorization header added");
        }
      },
      success: function (response) {
        console.log("GET successful:", url, response);
        if (callback) callback(response);
      },
      error: function (jqXHR, textStatus, errorThrown) {
        console.error("GET error:", url, jqXHR.status, jqXHR.responseText);
        if (error_callback) error_callback(jqXHR);
        else {
          if (jqXHR.responseJSON && jqXHR.responseJSON.message) {
            toastr.error(jqXHR.responseJSON.message);
          } else if (jqXHR.status === 401) {
            toastr.error("Session expired. Please login again.");
            localStorage.removeItem("user_token");
            window.location.replace("#login");
          } else {
            toastr.error("Request failed: " + jqXHR.status);
          }
        }
      },
    });
  },

  request: function (url, method, data, callback, error_callback) {
    console.log(
      method + " request to:",
      Constants.PROJECT_BASE_URL + url,
      "Data:",
      data
    );

    $.ajax({
      url: Constants.PROJECT_BASE_URL + url,
      type: method,
      data: JSON.stringify(data),
      contentType: "application/json",
      beforeSend: function (xhr) {
        let token = localStorage.getItem("user_token");
        if (token) {
          xhr.setRequestHeader("Authorization", "Bearer " + token);
        }
      },
      success: function (response) {
        console.log(method + " successful:", url, response);
        if (callback) callback(response);
      },
      error: function (jqXHR, textStatus, errorThrown) {
        console.error(
          method + " error:",
          url,
          jqXHR.status,
          jqXHR.responseText
        );
        if (error_callback) error_callback(jqXHR);
        else {
          if (jqXHR.responseJSON && jqXHR.responseJSON.message) {
            toastr.error(jqXHR.responseJSON.message);
          } else if (jqXHR.status === 401) {
            toastr.error("Session expired. Please login again.");
            localStorage.removeItem("user_token");
            window.location.replace("#login");
          } else {
            toastr.error("Request failed: " + jqXHR.status);
          }
        }
      },
    });
  },

  post: function (url, data, callback, error_callback) {
    this.request(url, "POST", data, callback, error_callback);
  },

  delete: function (url, data, callback, error_callback) {
    this.request(url, "DELETE", data, callback, error_callback);
  },

  patch: function (url, data, callback, error_callback) {
    this.request(url, "PATCH", data, callback, error_callback);
  },

  put: function (url, data, callback, error_callback) {
    this.request(url, "PUT", data, callback, error_callback);
  },
};
