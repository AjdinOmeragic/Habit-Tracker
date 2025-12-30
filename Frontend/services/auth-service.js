let AuthService = {
  init: function () {
    $("#login-form").validate({
      rules: {
        email: {
          required: true,
          email: true,
        },
        password: {
          required: true,
          minlength: 6,
        },
      },
      messages: {
        email: "Please enter a valid email address",
        password: {
          required: "Please enter your password",
          minlength: "Password must be at least 6 characters long",
        },
      },
      submitHandler: function (form) {
        const entity = Object.fromEntries(new FormData(form).entries());
        AuthService.login(entity);
      },
    });

    $("#register-form").validate({
      rules: {
        username: {
          required: true,
          minlength: 3,
          maxlength: 50,
        },
        email: {
          required: true,
          email: true,
        },
        password: {
          required: true,
          minlength: 6,
          maxlength: 20,
        },
      },
      messages: {
        username: {
          required: "Please enter a username",
          minlength: "Username must be at least 3 characters",
          maxlength: "Username cannot exceed 50 characters",
        },
        email: "Please enter a valid email address",
        password: {
          required: "Please enter a password",
          minlength: "Password must be at least 6 characters long",
          maxlength: "Password cannot exceed 20 characters",
        },
      },
      submitHandler: function (form) {
        const entity = Object.fromEntries(new FormData(form).entries());
        AuthService.register(entity);
      },
    });
  },

  login: function (entity) {
    $.blockUI({
      message:
        '<div class="blockui-message"><i class="fas fa-spinner fa-spin"></i><h3>Logging in...</h3></div>',
      css: {
        border: "none",
        padding: "15px",
        backgroundColor: "#000",
        opacity: 0.8,
        color: "#fff",
        borderRadius: "10px",
      },
    });

    RestClient.post(
      "auth/login",
      entity,
      function (response) {
        localStorage.setItem("user_token", response.data.token);
        if (response.data.user) {
          localStorage.setItem("user_info", JSON.stringify(response.data.user));
        } else {
          const user = AuthService.getCurrentUserFromToken();
          if (user) {
            localStorage.setItem("user_info", JSON.stringify(user));
          }
        }

        $.unblockUI();
        toastr.success("Welcome back!");
        AuthService.updateNavigation();
        window.location.replace("#habits");
      },
      function (error) {
        $.unblockUI();
        if (error.responseJSON && error.responseJSON.message) {
          toastr.error(error.responseJSON.message);
        } else {
          toastr.error("Login failed. Please check your credentials.");
        }
      }
    );
  },

  register: function (entity) {
    $.blockUI({
      message:
        '<div class="blockui-message"><i class="fas fa-spinner fa-spin"></i><h3>Creating account...</h3></div>',
      css: {
        border: "none",
        padding: "15px",
        backgroundColor: "#000",
        opacity: 0.8,
        color: "#fff",
        borderRadius: "10px",
      },
    });

    RestClient.post(
      "auth/register",
      entity,
      function (response) {
        $.unblockUI();
        toastr.success("Account created! Please login.");
        window.location.replace("#login");
      },
      function (error) {
        $.unblockUI();
        if (error.responseJSON && error.responseJSON.message) {
          toastr.error(error.responseJSON.message);
        } else {
          toastr.error("Registration failed. Please try again.");
        }
      }
    );
  },

  logout: function () {
    $.blockUI({
      message:
        '<div class="blockui-message"><i class="fas fa-spinner fa-spin"></i><h3>Logging out...</h3></div>',
      css: {
        border: "none",
        padding: "15px",
        backgroundColor: "#000",
        opacity: 0.8,
        color: "#fff",
        borderRadius: "10px",
      },
    });

    setTimeout(function () {
      localStorage.removeItem("user_token");
      localStorage.removeItem("user_info");
      $.unblockUI();
      toastr.success("Logged out successfully");
      AuthService.updateNavigation();
      window.location.replace("#home");
    }, 500);
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
