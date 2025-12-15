let PostService = {
  init: function () {
    if (!BaseService.requireAuth()) return;
    this.loadPosts();
    BaseService.setupForm("#create-post-form", (data) => this.createPost(data));
  },

  loadPosts: function () {
    BaseService.loadData("posts", (posts) => {
      if (posts?.length) {
        this.renderPosts(posts);
      } else {
        this.showNoPosts();
      }
    });
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
              }" placeholder="Add a comment..." rows="2"></textarea>
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
    BaseService.createData("posts", postData, () => this.loadPosts());
  },

  deletePost: function (postId) {
    if (confirm("Delete this post?")) {
      BaseService.deleteData("posts", postId, () => this.loadPosts());
    }
  },

  toggleLike: function (postId) {
    const user = BaseService.getCurrentUser();
    if (!user) {
      toastr.warning("Please login to like posts");
      return;
    }

    BaseService.createData("likes/toggle", { post_id: postId }, (response) => {
      const likeCountSpan = $(`#like-count-${postId}`);
      const currentLikes = parseInt(likeCountSpan.text()) || 0;
      if (response.action === "liked") {
        likeCountSpan.text(currentLikes + 1);
        toastr.success("Post liked!");
      } else {
        likeCountSpan.text(Math.max(0, currentLikes - 1));
        toastr.info("Like removed");
      }
    });
  },

  showComments: function (postId) {
    const commentsSection = $(`#comments-${postId}`);
    commentsSection.toggle();
    this.loadComments(postId);
  },

  loadComments: function (postId) {
    BaseService.loadData(`comments/post/${postId}`, (comments) => {
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
    });
  },

  addComment: function (postId) {
    const content = $(`#comment-input-${postId}`).val().trim();
    if (!content) {
      toastr.warning("Please enter a comment");
      return;
    }

    BaseService.createData(
      "comments",
      { content: content, post_id: postId },
      () => {
        $(`#comment-input-${postId}`).val("");
        this.loadComments(postId);
      }
    );
  },
};
