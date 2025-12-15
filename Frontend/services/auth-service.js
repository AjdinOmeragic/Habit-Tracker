let AuthService = {
  init: function () {
    $("#login-form").submit(function (e) {
      e.preventDefault();
      const entity = Object.fromEntries(new FormData(this).entries());
      AuthService.login(entity);
    });

    $("#register-form").submit(function (e) {
      e.preventDefault();
      const entity = Object.fromEntries(new FormData(this).entries());
      AuthService.register(entity);
    });
  },

  login: function (entity) {
    RestClient.post("auth/login", entity, function (response) {
      localStorage.setItem("user_token", response.data.token);
      if (response.data.user) {
        localStorage.setItem("user_info", JSON.stringify(response.data.user));
      } else {
        const user = AuthService.getCurrentUserFromToken();
        if (user) {
          localStorage.setItem("user_info", JSON.stringify(user));
        }
      }

      toastr.success("Welcome back!");
      AuthService.updateNavigation();
      window.location.replace("#habits");
    });
  },

  register: function (entity) {
    RestClient.post("auth/register", entity, function (response) {
      toastr.success("Account created! Please login.");
      window.location.replace("#login");
    });
  },

  logout: function () {
    localStorage.removeItem("user_token");
    localStorage.removeItem("user_info");
    toastr.success("Logged out successfully");
    AuthService.updateNavigation();
    window.location.replace("#home");
  },

  updateNavigation: function () {
    const token = localStorage.getItem("user_token");
    let user = this.getCurrentUser();
    let html = "";

    if (token && user) {
      html = `
        <li><a href="#home">Home</a></li>
        <li><a href="#habits">My Habits</a></li>
        <li><a href="#community">Community</a></li>
        <li><a href="#profile">Profile</a></li>
        <li><a href="#about">About</a></li>
      `;

      if (user.role === Constants.ADMIN_ROLE) {
        html += `<li><a href="#admin">Admin</a></li>`;
      }
      const username = user.username || user.email || "User";
      html += `<li><a href="#" onclick="AuthService.logout()">Logout (${username})</a></li>`;
    } else {
      html = `
        <li><a href="#home">Home</a></li>
        <li><a href="#login">Login</a></li>
        <li><a href="#register">Register</a></li>
        <li><a href="#about">About</a></li>
      `;
    }

    $("nav ul").html(html);
    $(".footer-col:first-child ul").html(html);
  },

  getCurrentUser: function () {
    const userInfo = localStorage.getItem("user_info");
    if (userInfo) {
      const user = JSON.parse(userInfo);
      if (user && (user.username || user.email || user.id)) {
        return user;
      }
    }
    return this.getCurrentUserFromToken();
  },

  getCurrentUserFromToken: function () {
    const token = localStorage.getItem("user_token");
    if (!token) return null;

    try {
      const payload = JSON.parse(atob(token.split(".")[1]));
      const user = payload.user || payload;

      if (user && (user.id || user.username || user.email)) {
        return {
          id: user.id,
          username: user.username || user.email,
          email: user.email,
          role: user.role,
        };
      }
    } catch (e) {
      return null;
    }
    return null;
  },

  isLoggedIn: function () {
    return !!localStorage.getItem("user_token");
  },

  requireAuth: function () {
    if (!this.isLoggedIn()) {
      toastr.warning("Please login first");
      window.location.replace("#login");
      return false;
    }
    return true;
  },
};
