let BaseService = {
  getCurrentUser: function () {
    return AuthService.getCurrentUser();
  },

  requireAuth: function () {
    return AuthService.requireAuth();
  },

  isAdmin: function () {
    const user = this.getCurrentUser();
    return user && user.role === Constants.ADMIN_ROLE;
  },

  loadData: function (endpoint, successCallback) {
    RestClient.get(endpoint, function (response) {
      successCallback(response);
    });
  },

  createData: function (endpoint, data, successCallback) {
    RestClient.post(endpoint, data, function (response) {
      toastr.success("Created successfully!");
      successCallback(response);
    });
  },

  updateData: function (endpoint, data, successCallback) {
    RestClient.put(endpoint, data, function (response) {
      toastr.success("Updated successfully!");
      successCallback(response);
    });
  },

  deleteData: function (endpoint, id, successCallback, customMessage = null) {
    if (!confirm(customMessage || "Are you sure?")) return;

    RestClient.delete(`${endpoint}/${id}`, {}, function (response) {
      toastr.success("Deleted successfully!");
      successCallback(response);
    });
  },

  setupForm: function (formId, submitCallback) {
    $(formId).submit(function (e) {
      e.preventDefault();
      const data = Object.fromEntries(new FormData(this).entries());
      submitCallback(data);
      this.reset();
    });
  },

  formatDate: function (dateString) {
    const date = new Date(dateString);
    return date.toLocaleDateString("en-US", {
      year: "numeric",
      month: "short",
      day: "numeric",
      hour: "2-digit",
      minute: "2-digit",
    });
  },

  formatShortDate: function (dateString) {
    const date = new Date(dateString);
    return date.toLocaleDateString("en-US", {
      month: "short",
      day: "numeric",
      hour: "2-digit",
      minute: "2-digit",
    });
  },
};
