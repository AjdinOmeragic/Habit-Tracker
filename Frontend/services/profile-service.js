let ProfileService = {
  init: function () {
    if (!BaseService.requireAuth()) return;
    this.loadUserProfile();
    this.loadStatistics();
  },

  loadUserProfile: function () {
    const user = AuthService.getCurrentUser();
    if (!user) {
      console.log("No user found for profile");
      return;
    }

    console.log("Profile user data:", user);

    const usernameElem = $("#profile-username");
    const emailElem = $("#profile-email");
    const roleElem = $("#profile-role");
    const welcomeElem = $("#profile-welcome");

    if (usernameElem.length) {
      if (usernameElem.is("input, textarea, select")) {
        usernameElem.val(user.username || "N/A");
      } else {
        usernameElem.text(user.username || "N/A");
      }
    }

    if (emailElem.length) {
      if (emailElem.is("input, textarea, select")) {
        emailElem.val(user.email || "N/A");
      } else {
        emailElem.text(user.email || "N/A");
      }
    }

    if (roleElem.length) {
      const roleText =
        user.role === "admin" ? "Administrator" : "Standard User";
      if (roleElem.is("input, textarea, select")) {
        roleElem.val(roleText);
      } else {
        roleElem.text(roleText);
      }
    }

    if (welcomeElem.length) {
      welcomeElem.text(
        `Welcome back, ${user.username || user.email || "User"}!`
      );
    }
  },

  loadStatistics: function () {
    const user = AuthService.getCurrentUser();
    if (!user?.id) return;

    // Load habits
    BaseService.loadData(`habits/user/${user.id}`, (habits) => {
      $("#habits-count").text(habits?.length || 0);
    });

    // Load posts
    BaseService.loadData(`posts/user/${user.id}`, (posts) => {
      $("#posts-count").text(posts?.length || 0);
    });
  },
};
