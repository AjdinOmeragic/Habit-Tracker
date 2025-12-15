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

  loadUsers: function () {
    BaseService.loadData("users", (users) => {
      this.renderItems("#users-container", users, "user");
      $("#users-count").text(`(${users?.length || 0} users)`);
    });
  },

  loadPosts: function () {
    BaseService.loadData("posts", (posts) => {
      this.renderItems("#posts-container", posts, "post");
      $("#posts-count").text(`(${posts?.length || 0} posts)`);
    });
  },

  loadHabits: function () {
    BaseService.loadData("habits", (habits) => {
      this.renderItems("#habits-container", habits, "habit");
      $("#habits-count").text(`(${habits?.length || 0} habits)`);
    });
  },

  loadComments: function () {
    BaseService.loadData("comments", (comments) => {
      this.renderItems("#comments-container", comments, "comment");
      $("#comments-count").text(`(${comments?.length || 0} comments)`);
    });
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
                ? `<button class="btn btn-small btn-primary" onclick="AdminService.promoteToAdmin(${item.id})">Make Admin</button>`
                : ""
            }
            ${
              item.role === "admin"
                ? `<button class="btn btn-small btn-secondary" onclick="AdminService.demoteFromAdmin(${item.id})">Remove Admin</button>`
                : ""
            }
            ${
              item.role !== "admin"
                ? `<button class="btn btn-small btn-danger" onclick="AdminService.deleteUser(${item.id})">Delete</button>`
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
          })">Delete Post</button>
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
          })">Delete Habit</button>
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
          })">Delete Comment</button>
        </div>
      `;
    }
  },

  promoteToAdmin: function (userId) {
    if (confirm("Promote this user to admin?")) {
      BaseService.updateData(`users/${userId}`, { role: "admin" }, () =>
        this.loadUsers()
      );
    }
  },

  demoteFromAdmin: function (userId) {
    if (confirm("Remove admin privileges?")) {
      BaseService.updateData(`users/${userId}`, { role: "user" }, () =>
        this.loadUsers()
      );
    }
  },

  deleteUser: function (userId) {
    if (confirm("Delete this user and all their data?")) {
      BaseService.deleteData("users", userId, () => this.loadUsers());
    }
  },

  deletePost: function (postId) {
    if (confirm("Delete this post?")) {
      BaseService.deleteData("posts", postId, () => this.loadPosts());
    }
  },

  deleteHabit: function (habitId) {
    if (confirm("Delete this habit?")) {
      BaseService.deleteData("habits", habitId, () => this.loadHabits());
    }
  },

  deleteComment: function (commentId) {
    if (confirm("Delete this comment?")) {
      BaseService.deleteData("comments", commentId, () => this.loadComments());
    }
  },
};
