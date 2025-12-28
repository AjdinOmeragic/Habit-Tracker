let HabitService = {
  init: function () {
    if (!BaseService.requireAuth()) return;
    this.loadHabits();

    $("#add-habit-form").validate({
      rules: {
        name: {
          required: true,
          minlength: 2,
          maxlength: 100,
        },
        category: {
          required: true,
        },
      },
      messages: {
        name: {
          required: "Please enter a habit name",
          minlength: "Habit name must be at least 2 characters",
          maxlength: "Habit name cannot exceed 100 characters",
        },
        category: "Please select a category",
      },
      submitHandler: function (form) {
        const data = Object.fromEntries(new FormData(form).entries());
        HabitService.addHabit(data);
        form.reset();
      },
    });
  },

  loadHabits: function () {
    const user = BaseService.getCurrentUser();
    if (!user?.id) return;

    if ($("#habits-container").is(":empty")) {
      $("#habits-container").html(`
        <div class="card">
          <i class="fas fa-spinner fa-spin"></i>
          <p>Loading habits...</p>
        </div>
      `);
    }

    $("#habits-container").block({
      message:
        '<div class="blockui-message"><i class="fas fa-spinner fa-spin"></i><h4>Loading habits...</h4></div>',
      css: {
        border: "none",
        padding: "15px",
        backgroundColor: "rgba(0,0,0,0.7)",
        color: "#fff",
        borderRadius: "10px",
      },
    });

    BaseService.loadData(
      "habits/user/" + user.id,
      (habits) => {
        $("#habits-container").unblock();
        if (Array.isArray(habits) && habits.length) {
          this.renderHabits(habits);
        } else {
          this.showNoHabits();
        }
      },
      function (error) {
        $("#habits-container").unblock();
        toastr.error("Failed to load habits");
      }
    );
  },

  renderHabits: function (habits) {
    let html = "";
    habits.forEach((habit) => {
      html += `
        <div class="card habit-card" data-habit-id="${habit.id}">
          <div class="habit-header">
            <h3>${habit.name}</h3>
            <span class="habit-category ${habit.category}">${habit.category}</span>
          </div>
          <div class="habit-actions">
            <button class="btn btn-primary" onclick="HabitService.markComplete(${habit.id})">
              <i class="fas fa-check"></i> Done
            </button>
            <button class="btn btn-secondary" onclick="HabitService.openEditModal(${habit.id})">
              <i class="fas fa-edit"></i> Edit
            </button>
            <button class="btn btn-danger" onclick="HabitService.deleteHabit(${habit.id})">
              <i class="fas fa-trash"></i> Delete
            </button>
          </div>
        </div>
      `;
    });
    $("#habits-container").html(html);
  },

  showNoHabits: function () {
    $("#habits-container").html(`
      <div class="card no-habits">
        <i class="fas fa-list-check"></i>
        <p>No habits yet. Add your first one above!</p>
      </div>
    `);
  },

  addHabit: function (habit) {
    $.blockUI({
      message:
        '<div class="blockui-message"><i class="fas fa-spinner fa-spin"></i><h4>Adding habit...</h4></div>',
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
      "habits",
      habit,
      () => {
        $.unblockUI();
        this.loadHabits();
      },
      function (error) {
        $.unblockUI();
        if (error.responseJSON && error.responseJSON.message) {
          toastr.error(error.responseJSON.message);
        } else {
          toastr.error("Failed to add habit");
        }
      }
    );
  },

  markComplete: function (habitId) {
    $.blockUI({
      message:
        '<div class="blockui-message"><i class="fas fa-spinner fa-spin"></i><h4>Marking as complete...</h4></div>',
      css: {
        border: "none",
        padding: "15px",
        backgroundColor: "#000",
        opacity: 0.8,
        color: "#fff",
        borderRadius: "10px",
      },
    });

    const today = new Date().toISOString().split("T")[0];
    BaseService.createData(
      "completions",
      {
        habit_id: parseInt(habitId),
        completion_date: today,
      },
      () => {
        $.unblockUI();
        toastr.success("Habit marked complete!");
        this.loadHabits();
      },
      function (error) {
        $.unblockUI();
        if (error.responseJSON && error.responseJSON.message) {
          toastr.error(error.responseJSON.message);
        } else {
          toastr.error("Failed to mark habit as complete");
        }
      }
    );
  },

  deleteHabit: function (habitId) {
    if (confirm("Are you sure you want to delete this habit?")) {
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

  openEditModal: function (habitId) {
    const card = $(`.habit-card[data-habit-id="${habitId}"]`);
    const name = card.find("h3").text();
    const category = card.find(".habit-category").text().toLowerCase();

    const modalHtml = `
      <div id="edit-habit-modal" style="
        position: fixed; top: 0; left: 0; width: 100%; height: 100%; 
        background: rgba(0,0,0,0.5); display: flex; align-items: center; 
        justify-content: center; z-index: 1000;
      ">
        <div class="card" style="width: 90%; max-width: 400px;">
          <h2><i class="fas fa-edit"></i> Edit Habit</h2>
          <form id="edit-habit-form" novalidate>
            <div class="form-group">
              <input type="text" id="edit-habit-name" value="${name}" 
                class="form-input" placeholder="Habit name" required
                minlength="2" maxlength="100">
              <div class="invalid-feedback" style="display: none;">
                Habit name must be 2-100 characters
              </div>
            </div>
            <div class="form-group">
              <select id="edit-habit-category" class="form-input" required>
                <option value="">Select category</option>
                <option value="health" ${
                  category === "health" ? "selected" : ""
                }>Health & Fitness</option>
                <option value="learning" ${
                  category === "learning" ? "selected" : ""
                }>Learning</option>
                <option value="productivity" ${
                  category === "productivity" ? "selected" : ""
                }>Productivity</option>
                <option value="mindfulness" ${
                  category === "mindfulness" ? "selected" : ""
                }>Mindfulness</option>
              </select>
              <div class="invalid-feedback" style="display: none;">
                Please select a category
              </div>
            </div>
            <div class="habit-actions" style="margin-top: 1.5rem;">
              <button type="submit" class="btn btn-primary">
                <i class="fas fa-save"></i> Save Changes
              </button>
              <button type="button" class="btn btn-secondary" onclick="HabitService.closeEditModal()">
                <i class="fas fa-times"></i> Cancel
              </button>
            </div>
          </form>
        </div>
      </div>
    `;
    $("body").append(modalHtml);

    $("#edit-habit-form").validate({
      rules: {
        "edit-habit-name": {
          required: true,
          minlength: 2,
          maxlength: 100,
        },
        "edit-habit-category": {
          required: true,
        },
      },
      messages: {
        "edit-habit-name": {
          required: "Please enter a habit name",
          minlength: "Habit name must be at least 2 characters",
          maxlength: "Habit name cannot exceed 100 characters",
        },
        "edit-habit-category": "Please select a category",
      },
      submitHandler: function (form) {
        HabitService.updateHabit(habitId);
        return false;
      },
    });

    $("#edit-habit-name").focus();
  },

  closeEditModal: function () {
    $("#edit-habit-modal").remove();
  },

  updateHabit: function (habitId) {
    const name = $("#edit-habit-name").val().trim();
    const category = $("#edit-habit-category").val();

    $.blockUI({
      message:
        '<div class="blockui-message"><i class="fas fa-spinner fa-spin"></i><h4>Updating habit...</h4></div>',
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
      `habits/${habitId}`,
      {
        name: name,
        category: category,
      },
      () => {
        $.unblockUI();
        toastr.success("Habit updated successfully!");
        this.closeEditModal();
        this.loadHabits();
      },
      function (error) {
        $.unblockUI();
        if (error.responseJSON && error.responseJSON.message) {
          toastr.error(error.responseJSON.message);
        } else {
          toastr.error("Failed to update habit");
        }
      }
    );
  },
};
