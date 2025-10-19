$(document).ready(function () {
  var app = $.spapp({
    pageNotFound: "error_404",
    templateDir: "./Views/",
    defaultView: "#home",
  });

  app.route({ view: "home", load: "home.html" }); //OK
  app.route({ view: "habits", load: "habits.html" }); //OK
  app.route({ view: "community", load: "community.html" }); //OK
  app.route({ view: "login", load: "login.html" }); //OK
  app.route({ view: "register", load: "register.html" }); //OK
  app.route({ view: "about", load: "about.html" }); //OK
  app.route({ view: "profile", load: "profile.html" }); //OK
  app.route({ view: "admin", load: "admin.html" }); //OK

  // run app
  app.run();
});
