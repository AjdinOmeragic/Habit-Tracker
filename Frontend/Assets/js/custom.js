$(document).ready(function () {
  var app = $.spapp({
    pageNotFound: "error_404",
    templateDir: "./Views/",
    defaultView: "#home",
  });
  app.route({
    view: "home",
    load: "home.html",
    onReady: function () {
      AuthService.updateNavigation();
    },
  });

  app.route({
    view: "habits",
    load: "habits.html",
    onReady: function () {
      AuthService.updateNavigation();
      if (AuthService.requireAuth()) {
        setTimeout(function () {
          if (typeof HabitService !== "undefined") {
            HabitService.init();
          }
        }, 100);
      }
    },
  });

  app.route({
    view: "community",
    load: "community.html",
    onReady: function () {
      AuthService.updateNavigation();
      if (AuthService.requireAuth()) {
        setTimeout(function () {
          if (typeof PostService !== "undefined") {
            PostService.init();
          }
        }, 100);
      }
    },
  });

  app.route({
    view: "login",
    load: "login.html",
    onReady: function () {
      AuthService.updateNavigation();
      if (AuthService.isLoggedIn()) {
        window.location.replace("#habits");
        return;
      }
      setTimeout(function () {
        if (typeof AuthService !== "undefined") {
          AuthService.init();
        }
      }, 100);
    },
  });

  app.route({
    view: "register",
    load: "register.html",
    onReady: function () {
      AuthService.updateNavigation();
      if (AuthService.isLoggedIn()) {
        window.location.replace("#habits");
        return;
      }
      setTimeout(function () {
        if (typeof AuthService !== "undefined") {
          AuthService.init();
        }
      }, 100);
    },
  });

  app.route({
    view: "about",
    load: "about.html",
    onReady: function () {
      AuthService.updateNavigation();
    },
  });

  app.route({
    view: "profile",
    load: "profile.html",
    onReady: function () {
      AuthService.updateNavigation();
      if (AuthService.requireAuth()) {
        setTimeout(function () {
          if (typeof ProfileService !== "undefined") {
            ProfileService.init();
          }
        }, 100);
      }
    },
  });

  app.route({
    view: "admin",
    load: "admin.html",
    onReady: function () {
      AuthService.updateNavigation();
      let user = AuthService.getCurrentUser();
      if (user && user.role === Constants.ADMIN_ROLE) {
        setTimeout(function () {
          if (typeof AdminService !== "undefined") {
            AdminService.init();
          }
        }, 100);
      } else {
        toastr.error("Admin access required");
        window.location.replace("#home");
      }
    },
  });

  app.run();

  setTimeout(function () {
    AuthService.updateNavigation();
  }, 100);
});
