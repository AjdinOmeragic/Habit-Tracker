let AdminService = {
  init: function () {
    if (!BaseService.isAdmin()) {
      toastr.error("Admin access required");
      window.location.replace("#home");
      return;
    }

    this.loadUsers();
    this.loadPosts();
    this.loadHabits();
    this.loadComments();
  },

  loadAllData: function () {
    $.blockUI({
      message:
        '<div class="blockui-message"><i class="fas fa-spinner fa-spin"></i><h4>Loading all data...</h4></div>',
      css: {
        border: "none",
        padding: "15px",
        backgroundColor: "#000",
        opacity: 0.8,
        color: "#fff",
        borderRadius: "10px",
      },
    });

    this.loadUsers();
    this.loadPosts();
    this.loadHabits();
    this.loadComments();
    setTimeout(() => {
      $.unblockUI();
      toastr.success("All data reloaded successfully!");
    }, 1000);
  },

  clearCache: function () {
    if (confirm("Clear all cached data? This will refresh all lists.")) {
      $.blockUI({
        message:
          '<div class="blockui-message"><i class="fas fa-spinner fa-spin"></i><h4>Clearing cache...</h4></div>',
        css: {
          border: "none",
          padding: "15px",
          backgroundColor: "#000",
          opacity: 0.8,
          color: "#fff",
          borderRadius: "10px",
        },
      });

      $("#users-container").html(
        '<div class="list-item"><span>No users found</span></div>'
      );
      $("#posts-container").html(
        '<div class="list-item"><span>No posts found</span></div>'
      );
      $("#habits-container").html(
        '<div class="list-item"><span>No habits found</span></div>'
      );
      $("#comments-container").html(
        '<div class="list-item"><span>No comments found</span></div>'
      );

      $("#users-count").text("0");
      $("#posts-count").text("0");
      $("#habits-count").text("0");
      $("#comments-count").text("0");

      setTimeout(() => {
        this.loadAllData();
        $.unblockUI();
        toastr.success("Cache cleared and data reloaded!");
      }, 500);
    }
  },
  loadUsers: function () {
    $("#users-container").block({
      message:
        '<div class="blockui-message"><i class="fas fa-spinner fa-spin"></i><h5>Loading users...</h5></div>',
      css: {
        border: "none",
        padding: "10px",
        backgroundColor: "rgba(0,0,0,0.7)",
        color: "#fff",
        borderRadius: "10px",
      },
    });

    BaseService.loadData(
      "users",
      (users) => {
        $("#users-container").unblock();
        this.renderItems("#users-container", users, "user");
        $("#users-count").text(`(${users?.length || 0} users)`);
      },
      function (error) {
        $("#users-container").unblock();
        toastr.error("Failed to load users");
      }
    );
  },

  loadPosts: function () {
    $("#posts-container").block({
      message:
        '<div class="blockui-message"><i class="fas fa-spinner fa-spin"></i><h5>Loading posts...</h5></div>',
      css: {
        border: "none",
        padding: "10px",
        backgroundColor: "rgba(0,0,0,0.7)",
        color: "#fff",
        borderRadius: "10px",
      },
    });

    BaseService.loadData(
      "posts",
      (posts) => {
        $("#posts-container").unblock();
        this.renderItems("#posts-container", posts, "post");
        $("#posts-count").text(`(${posts?.length || 0} posts)`);
      },
      function (error) {
        $("#posts-container").unblock();
        toastr.error("Failed to load posts");
      }
    );
  },

  loadHabits: function () {
    $("#habits-container").block({
      message:
        '<div class="blockui-message"><i class="fas fa-spinner fa-spin"></i><h5>Loading habits...</h5></div>',
      css: {
        border: "none",
        padding: "10px",
        backgroundColor: "rgba(0,0,0,0.7)",
        color: "#fff",
        borderRadius: "10px",
      },
    });

    BaseService.loadData(
      "habits",
      (habits) => {
        $("#habits-container").unblock();
        this.renderItems("#habits-container", habits, "habit");
        $("#habits-count").text(`(${habits?.length || 0} habits)`);
      },
      function (error) {
        $("#habits-container").unblock();
        toastr.error("Failed to load habits");
      }
    );
  },

  loadComments: function () {
    $("#comments-container").block({
      message:
        '<div class="blockui-message"><i class="fas fa-spinner fa-spin"></i><h5>Loading comments...</h5></div>',
      css: {
        border: "none",
        padding: "10px",
        backgroundColor: "rgba(0,0,0,0.7)",
        color: "#fff",
        borderRadius: "10px",
      },
    });

    BaseService.loadData(
      "comments",
      (comments) => {
        $("#comments-container").unblock();
        this.renderItems("#comments-container", comments, "comment");
        $("#comments-count").text(`(${comments?.length || 0} comments)`);
      },
      function (error) {
        $("#comments-container").unblock();
        toastr.error("Failed to load comments");
      }
    );
  },

  renderItems: function (containerId, items, type) {
    if (!items?.length) {
      $(containerId).html(
        `<div class="list-item"><span>No ${type}s found</span></div>`
      );
      return;
    }

    let html = "";
    items.forEach((item) => (html += this.getItemHtml(item, type)));
    $(containerId).html(html);
  },

  getItemHtml: function (item, type) {
    const date = BaseService.formatDate(item.created_at);

    if (type === "user") {
      return `
        <div class="list-item">
          <div>
            <strong>${item.username}</strong>
            <span>${item.email}</span>
            <div>
              <span class="badge ${
                item.role === "admin" ? "badge-admin" : "badge-user"
              }">${item.role}</span>
              <span>Joined: ${date}</span>
            </div>
          </div>
          <div>
            ${
              item.role !== "admin"
                ? `<button class="btn btn-small btn-primary" onclick="AdminService.promoteToAdmin(${item.id})">
                    <i class="fas fa-user-shield"></i> Make Admin
                   </button>`
                : ""
            }
            ${
              item.role === "admin"
                ? `<button class="btn btn-small btn-secondary" onclick="AdminService.demoteFromAdmin(${item.id})">
                    <i class="fas fa-user-minus"></i> Remove Admin
                   </button>`
                : ""
            }
            ${
              item.role !== "admin"
                ? `<button class="btn btn-small btn-danger" onclick="AdminService.deleteUser(${item.id})">
                    <i class="fas fa-trash"></i> Delete
                   </button>`
                : ""
            }
          </div>
        </div>
      `;
    }

    if (type === "post") {
      return `
        <div class="list-item">
          <div>
            <strong>"${item.title || "Untitled Post"}"</strong>
            <span>${item.content?.substring(0, 100) || "No content"}${
        item.content?.length > 100 ? "..." : ""
      }</span>
            <div>By: ${
              item.user_username || `User #${item.user_id}`
            } | ${date}</div>
          </div>
          <button class="btn btn-small btn-danger" onclick="AdminService.deletePost(${
            item.id
          })">
            <i class="fas fa-trash"></i> Delete Post
          </button>
        </div>
      `;
    }

    if (type === "habit") {
      return `
        <div class="list-item">
          <div>
            <strong>${item.name || "Unnamed Habit"}</strong>
            <span>Category: ${item.category || "Uncategorized"} | User: ${
        item.user_id
      }</span>
            <div>Created: ${date}</div>
          </div>
          <button class="btn btn-small btn-danger" onclick="AdminService.deleteHabit(${
            item.id
          })">
            <i class="fas fa-trash"></i> Delete Habit
          </button>
        </div>
      `;
    }

    if (type === "comment") {
      return `
        <div class="list-item">
          <div>
            <div>Post: ${item.post_id} | User: ${
        item.user_username || `User #${item.user_id}`
      }</div>
            <span>${item.content?.substring(0, 80) || "No content"}${
        item.content?.length > 80 ? "..." : ""
      }</span>
            <div>${date}</div>
          </div>
          <button class="btn btn-small btn-danger" onclick="AdminService.deleteComment(${
            item.id
          })">
            <i class="fas fa-trash"></i> Delete Comment
          </button>
        </div>
      `;
    }
  },

  promoteToAdmin: function (userId) {
    if (confirm("Promote this user to admin?")) {
      $.blockUI({
        message:
          '<div class="blockui-message"><i class="fas fa-spinner fa-spin"></i><h4>Promoting user...</h4></div>',
        css: {
          border: "none",
          padding: "15px",
          backgroundColor: "#000",
          opacity: 0.8,
          color: "#fff",
          borderRadius: "10px",
        },
      });

      BaseService.updateData(
        `users/${userId}`,
        { role: "admin" },
        () => {
          $.unblockUI();
          toastr.success("User promoted to admin successfully!");
          this.loadUsers();
        },
        function (error) {
          $.unblockUI();
          toastr.error("Failed to promote user");
        }
      );
    }
  },

  demoteFromAdmin: function (userId) {
    if (confirm("Remove admin privileges from this user?")) {
      $.blockUI({
        message:
          '<div class="blockui-message"><i class="fas fa-spinner fa-spin"></i><h4>Removing admin privileges...</h4></div>',
        css: {
          border: "none",
          padding: "15px",
          backgroundColor: "#000",
          opacity: 0.8,
          color: "#fff",
          borderRadius: "10px",
        },
      });

      BaseService.updateData(
        `users/${userId}`,
        { role: "user" },
        () => {
          $.unblockUI();
          toastr.success("Admin privileges removed!");
          this.loadUsers();
        },
        function (error) {
          $.unblockUI();
          toastr.error("Failed to remove admin privileges");
        }
      );
    }
  },

  deleteUser: function (userId) {
    if (confirm("Delete this user and all their data?")) {
      $.blockUI({
        message:
          '<div class="blockui-message"><i class="fas fa-spinner fa-spin"></i><h4>Deleting user...</h4></div>',
        css: {
          border: "none",
          padding: "15px",
          backgroundColor: "#000",
          opacity: 0.8,
          color: "#fff",
          borderRadius: "10px",
        },
      });

      BaseService.deleteData("users", userId, () => {
        $.unblockUI();
        toastr.success("User deleted successfully!");
        this.loadUsers();
      });
    }
  },

  deletePost: function (postId) {
    if (confirm("Delete this post?")) {
      $.blockUI({
        message:
          '<div class="blockui-message"><i class="fas fa-spinner fa-spin"></i><h4>Deleting post...</h4></div>',
        css: {
          border: "none",
          padding: "15px",
          backgroundColor: "#000",
          opacity: 0.8,
          color: "#fff",
          borderRadius: "10px",
        },
      });

      BaseService.deleteData("posts", postId, () => {
        $.unblockUI();
        toastr.success("Post deleted successfully!");
        this.loadPosts();
      });
    }
  },

  deleteHabit: function (habitId) {
    if (confirm("Delete this habit?")) {
      $.blockUI({
        message:
          '<div class="blockui-message"><i class="fas fa-spinner fa-spin"></i><h4>Deleting habit...</h4></div>',
        css: {
          border: "none",
          padding: "15px",
          backgroundColor: "#000",
          opacity: 0.8,
          color: "#fff",
          borderRadius: "10px",
        },
      });

      BaseService.deleteData("habits", habitId, () => {
        $.unblockUI();
        toastr.success("Habit deleted successfully!");
        this.loadHabits();
      });
    }
  },

  deleteComment: function (commentId) {
    if (confirm("Delete this comment?")) {
      $.blockUI({
        message:
          '<div class="blockui-message"><i class="fas fa-spinner fa-spin"></i><h4>Deleting comment...</h4></div>',
        css: {
          border: "none",
          padding: "15px",
          backgroundColor: "#000",
          opacity: 0.8,
          color: "#fff",
          borderRadius: "10px",
        },
      });

      BaseService.deleteData("comments", commentId, () => {
        $.unblockUI();
        toastr.success("Comment deleted successfully!");
        this.loadComments();
      });
    }
  },
};
