let HabitService = {
  init: function () {
    if (!BaseService.requireAuth()) return;
    this.loadHabits();
    BaseService.setupForm("#add-habit-form", (data) => this.addHabit(data));
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

    BaseService.loadData("habits/user/" + user.id, (habits) => {
      if (Array.isArray(habits) && habits.length) {
        this.renderHabits(habits);
      } else {
        this.showNoHabits();
      }
    });
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
            <button class="btn btn-primary" onclick="HabitService.markComplete(${habit.id})">✓ Done</button>
            <button class="btn btn-secondary" onclick="HabitService.openEditModal(${habit.id})">Edit</button>
            <button class="btn btn-danger" onclick="HabitService.deleteHabit(${habit.id})">Delete</button>
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
    BaseService.createData("habits", habit, () => this.loadHabits());
  },

  markComplete: function (habitId) {
    const today = new Date().toISOString().split("T")[0];
    BaseService.createData(
      "completions",
      {
        habit_id: parseInt(habitId),
        completion_date: today,
      },
      () => {
        toastr.success("Habit marked complete!");
        this.loadHabits();
      }
    );
  },

  deleteHabit: function (habitId) {
    if (confirm("Delete this habit?")) {
      BaseService.deleteData("habits", habitId, () => this.loadHabits());
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
          <h2>Edit Habit</h2>
          <form id="edit-habit-form">
            <div class="form-group">
              <input type="text" id="edit-habit-name" value="${name}" 
                class="form-input" placeholder="Habit name" required>
            </div>
            <div class="form-group">
              <select id="edit-habit-category" class="form-input" required>
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
            </div>
            <div class="habit-actions" style="margin-top: 1.5rem;">
              <button type="submit" class="btn btn-primary">Save</button>
              <button type="button" class="btn btn-secondary" onclick="HabitService.closeEditModal()">Cancel</button>
            </div>
          </form>
        </div>
      </div>
    `;
    $("body").append(modalHtml);

    $("#edit-habit-form").submit((e) => {
      e.preventDefault();
      this.updateHabit(habitId);
    });

    $("#edit-habit-name").focus();
  },

  closeEditModal: function () {
    $("#edit-habit-modal").remove();
  },

  updateHabit: function (habitId) {
    const name = $("#edit-habit-name").val().trim();
    const category = $("#edit-habit-category").val();

    if (!name) {
      toastr.warning("Please enter a habit name");
      return;
    }

    BaseService.updateData(
      `habits/${habitId}`,
      {
        name: name,
        category: category,
      },
      () => {
        toastr.success("Habit updated!");
        this.closeEditModal();
        this.loadHabits();
      }
    );
  },
};
