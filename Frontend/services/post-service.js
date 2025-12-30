let PostService = {
  init: function () {
    if (!BaseService.requireAuth()) return;
    this.loadPosts();
    $("#create-post-form").validate({
      rules: {
        title: {
          required: true,
          minlength: 3,
          maxlength: 200,
        },
        content: {
          required: true,
          minlength: 10,
          maxlength: 5000,
        },
      },
      messages: {
        title: {
          required: "Please enter a post title",
          minlength: "Title must be at least 3 characters",
          maxlength: "Title cannot exceed 200 characters",
        },
        content: {
          required: "Please enter post content",
          minlength: "Content must be at least 10 characters",
          maxlength: "Content cannot exceed 5000 characters",
        },
      },
      submitHandler: function (form) {
        const data = Object.fromEntries(new FormData(form).entries());
        PostService.createPost(data);
        form.reset();
      },
    });
  },

  loadPosts: function () {
    $("#posts-container").block({
      message:
        '<div class="blockui-message"><i class="fas fa-spinner fa-spin"></i><h4>Loading posts...</h4></div>',
      css: {
        border: "none",
        padding: "15px",
        backgroundColor: "rgba(0,0,0,0.7)",
        color: "#fff",
        borderRadius: "10px",
      },
    });

    BaseService.loadData(
      "posts",
      (posts) => {
        $("#posts-container").unblock();
        if (posts?.length) {
          this.renderPosts(posts);
        } else {
          this.showNoPosts();
        }
      },
      function (error) {
        $("#posts-container").unblock();
        toastr.error("Failed to load posts");
      }
    );
  },

  renderPosts: function (posts) {
    const currentUser = BaseService.getCurrentUser();
    let html = "";

    posts.forEach((post) => {
      html += `
        <div class="post-item card" data-post-id="${post.id}">
          <div class="post-header">
            <div class="post-user">
              <strong>${post.user_username || "User"}</strong>
              <span class="post-time">${BaseService.formatDate(
                post.created_at
              )}</span>
            </div>
            ${
              currentUser &&
              (currentUser.id === post.user_id || currentUser.role === "admin")
                ? `<button class="btn btn-small btn-danger" onclick="PostService.deletePost(${post.id})">
                <i class="fas fa-trash"></i>
              </button>`
                : ""
            }
          </div>
          <div class="post-content">
            <h3>${post.title || "Untitled Post"}</h3>
            <p>${post.content || "No content"}</p>
          </div>
          <div class="post-actions">
            <button class="vote-btn" onclick="PostService.toggleLike(${
              post.id
            })">
              <i class="fas fa-arrow-up"></i>
              <span id="like-count-${post.id}">0</span> likes
            </button>
            <button class="comment-btn" onclick="PostService.showComments(${
              post.id
            })">
              <i class="fas fa-comment"></i>
              <span id="comment-count-${post.id}">0</span> comments
            </button>
          </div>
          <div class="comments-section" id="comments-${
            post.id
          }" style="display: none;">
            <div class="add-comment">
              <textarea class="form-input" id="comment-input-${
                post.id
              }" placeholder="Add a comment (minimum 3 characters)..." rows="2"
              minlength="3" maxlength="1000" required></textarea>
              <div class="invalid-feedback" style="display: none; margin-top: 5px;">
                Comment must be 3-1000 characters
              </div>
              <button class="btn btn-primary btn-small" onclick="PostService.addComment(${
                post.id
              })">
                <i class="fas fa-paper-plane"></i> Post Comment
              </button>
            </div>
            <div class="comments-list" id="comments-list-${post.id}"></div>
          </div>
        </div>
      `;
    });

    $("#posts-container").html(html);
    posts.forEach((post) => {
      this.loadCommentsCount(post.id);
      this.loadLikesCount(post.id);
    });
  },

  showNoPosts: function () {
    $("#posts-container").html(`
      <div class="card" style="text-align: center; padding: 3rem">
        <i class="fas fa-comments" style="font-size: 3rem; color: var(--secondary-color);"></i>
        <p style="margin-top: 1rem; color: var(--text-secondary)">No posts yet. Be the first to post!</p>
      </div>
    `);
  },

  loadCommentsCount: function (postId) {
    BaseService.loadData(`comments/post/${postId}`, (comments) =>
      $(`#comment-count-${postId}`).text(comments?.length || 0)
    );
  },

  loadLikesCount: function (postId) {
    BaseService.loadData(`likes/post/${postId}`, (response) =>
      $(`#like-count-${postId}`).text(response?.likes_count || 0)
    );
  },

  createPost: function (postData) {
    $.blockUI({
      message:
        '<div class="blockui-message"><i class="fas fa-spinner fa-spin"></i><h4>Creating post...</h4></div>',
      css: {
        border: "none",
        padding: "15px",
        backgroundColor: "#000",
        opacity: 0.8,
        color: "#fff",
        borderRadius: "10px",
      },
    });

    BaseService.createData(
      "posts",
      postData,
      () => {
        $.unblockUI();
        toastr.success("Post created successfully!");
        this.loadPosts();
      },
      function (error) {
        $.unblockUI();
        if (error.responseJSON && error.responseJSON.message) {
          toastr.error(error.responseJSON.message);
        } else {
          toastr.error("Failed to create post");
        }
      }
    );
  },

  deletePost: function (postId) {
    if (confirm("Are you sure you want to delete this post?")) {
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

  toggleLike: function (postId) {
    const user = BaseService.getCurrentUser();
    if (!user) {
      toastr.warning("Please login to like posts");
      return;
    }

    $.blockUI({
      message:
        '<div class="blockui-message"><i class="fas fa-spinner fa-spin"></i><h4>Processing...</h4></div>',
      css: {
        border: "none",
        padding: "15px",
        backgroundColor: "#000",
        opacity: 0.8,
        color: "#fff",
        borderRadius: "10px",
      },
      timeout: 1000,
    });

    BaseService.createData(
      "likes/toggle",
      { post_id: postId },
      (response) => {
        const likeCountSpan = $(`#like-count-${postId}`);
        const currentLikes = parseInt(likeCountSpan.text()) || 0;
        if (response.action === "liked") {
          likeCountSpan.text(currentLikes + 1);
          toastr.success("Post liked!");
        } else {
          likeCountSpan.text(Math.max(0, currentLikes - 1));
          toastr.info("Like removed");
        }
      },
      function (error) {
        if (error.responseJSON && error.responseJSON.message) {
          toastr.error(error.responseJSON.message);
        } else {
          toastr.error("Failed to like/unlike post");
        }
      }
    );
  },

  showComments: function (postId) {
    const commentsSection = $(`#comments-${postId}`);
    commentsSection.toggle();
    this.loadComments(postId);
  },

  loadComments: function (postId) {
    $(`#comments-list-${postId}`).block({
      message:
        '<div class="blockui-message"><i class="fas fa-spinner fa-spin"></i><h5>Loading comments...</h5></div>',
      css: {
        border: "none",
        padding: "10px",
        backgroundColor: "rgba(0,0,0,0.5)",
        color: "#fff",
        borderRadius: "5px",
      },
    });

    BaseService.loadData(
      `comments/post/${postId}`,
      (comments) => {
        $(`#comments-list-${postId}`).unblock();
        let html = "";
        if (!comments?.length) {
          html = `<div class="no-comments"><i class="fas fa-comment-slash"></i><p>No comments yet. Be the first to comment!</p></div>`;
        } else {
          comments.forEach((comment) => {
            html += `
            <div class="comment-item">
              <div class="comment-header">
                <strong>${comment.user_username || "Anonymous"}</strong>
                <span class="comment-time">${BaseService.formatShortDate(
                  comment.created_at
                )}</span>
              </div>
              <p>${comment.content}</p>
            </div>
          `;
          });
        }
        $(`#comments-list-${postId}`).html(html);
        $(`#comment-count-${postId}`).text(comments?.length || 0);
      },
      function (error) {
        $(`#comments-list-${postId}`).unblock();
        toastr.error("Failed to load comments");
      }
    );
  },

  addComment: function (postId) {
    const content = $(`#comment-input-${postId}`).val().trim();

    if (!content) {
      toastr.warning("Please enter a comment");
      $(`#comment-input-${postId}`).focus();
      return;
    }

    if (content.length < 3) {
      toastr.warning("Comment must be at least 3 characters");
      $(`#comment-input-${postId}`).focus();
      return;
    }

    $.blockUI({
      message:
        '<div class="blockui-message"><i class="fas fa-spinner fa-spin"></i><h4>Posting comment...</h4></div>',
      css: {
        border: "none",
        padding: "15px",
        backgroundColor: "#000",
        opacity: 0.8,
        color: "#fff",
        borderRadius: "10px",
      },
    });

    BaseService.createData(
      "comments",
      { content: content, post_id: postId },
      () => {
        $.unblockUI();
        $(`#comment-input-${postId}`).val("");
        toastr.success("Comment posted!");
        this.loadComments(postId);
      },
      function (error) {
        $.unblockUI();
        if (error.responseJSON && error.responseJSON.message) {
          toastr.error(error.responseJSON.message);
        } else {
          toastr.error("Failed to post comment");
        }
      }
    );
  },
};
