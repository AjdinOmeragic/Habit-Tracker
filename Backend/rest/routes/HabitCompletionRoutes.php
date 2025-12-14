<?php
require_once __DIR__ . '/../../data/Roles.php';

/**
 * @OA\Get(
 *     path="/completions/habit/{habit_id}",
 *     tags={"completions"},
 *     summary="Get completions by habit ID - HABIT OWNER OR ADMIN",
 *     security={{"ApiKey": {}}},
 *     @OA\Parameter(
 *         name="habit_id",
 *         in="path",
 *         required=true,
 *         description="ID of the habit",
 *         @OA\Schema(type="integer", example=1)
 *     ),
 *     @OA\Response(
 *         response=200,
 *         description="Array of completions for the specified habit"
 *     )
 * )
 */
Flight::route('GET /completions/habit/@habit_id', function($habit_id) {
    $current_user = Flight::get('user');
    $habit = Flight::habitService()->get_by_id($habit_id);
    
    if (!$habit) {
        Flight::halt(404, "Habit not found");
    }
    
    // Only habit owner or admin can view completions
    if ($current_user->role !== Roles::ADMIN && $current_user->id != $habit['user_id']) {
        Flight::halt(403, "Unauthorized");
    }
    
    Flight::json(Flight::habitCompletionService()->get_by_habit_id($habit_id));
});

/**
 * @OA\Post(
 *     path="/completions",
 *     tags={"completions"},
 *     summary="Mark a habit as completed - HABIT OWNER ONLY",
 *     security={{"ApiKey": {}}},
 *     @OA\RequestBody(
 *         required=true,
 *         @OA\JsonContent(
 *             required={"habit_id", "completion_date"},
 *             @OA\Property(property="habit_id", type="integer", example=1),
 *             @OA\Property(property="completion_date", type="string", format="date", example="2024-01-15")
 *         )
 *     ),
 *     @OA\Response(
 *         response=200,
 *         description="Habit marked as completed"
 *     )
 * )
 */
Flight::route('POST /completions', function() {
    $current_user = Flight::get('user');
    $data = Flight::request()->data->getData();
    $habit_id = $data['habit_id'];
    
    $habit = Flight::habitService()->get_by_id($habit_id);
    if (!$habit) {
        Flight::halt(404, "Habit not found");
    }
    
    // Only habit owner can mark completions
    if ($current_user->id != $habit['user_id']) {
        Flight::halt(403, "Unauthorized - You can only mark your own habits as completed");
    }
    
    Flight::json(Flight::habitCompletionService()->mark_completed($data));
});

/**
 * @OA\Get(
 *     path="/completions/check/{habit_id}/{date}",
 *     tags={"completions"},
 *     summary="Check if habit is completed on specific date - HABIT OWNER OR ADMIN",
 *     security={{"ApiKey": {}}},
 *     @OA\Parameter(
 *         name="habit_id",
 *         in="path",
 *         required=true,
 *         description="ID of the habit",
 *         @OA\Schema(type="integer", example=1)
 *     ),
 *     @OA\Parameter(
 *         name="date",
 *         in="path",
 *         required=true,
 *         description="Date to check (YYYY-MM-DD)",
 *         @OA\Schema(type="string", example="2024-01-15")
 *     ),
 *     @OA\Response(
 *         response=200,
 *         description="Check result"
 *     )
 * )
 */
Flight::route('GET /completions/check/@habit_id/@date', function($habit_id, $date) {
    $current_user = Flight::get('user');
    $habit = Flight::habitService()->get_by_id($habit_id);
    
    if (!$habit) {
        Flight::halt(404, "Habit not found");
    }
    if ($current_user->role !== Roles::ADMIN && $current_user->id != $habit['user_id']) {
        Flight::halt(403, "Unauthorized");
    }
    
    $isCompleted = Flight::habitCompletionService()->is_completed_on_date($habit_id, $date);
    Flight::json(['completed' => $isCompleted]);
});

/**
 * @OA\Get(
 *     path="/habit-completions",
 *     tags={"completions"},
 *     summary="Get all habit completions - ADMIN ONLY",
 *     security={{"ApiKey": {}}},
 *     @OA\Response(
 *         response=200,
 *         description="Array of all habit completions"
 *     )
 * )
 */
Flight::route('GET /habit-completions', function() {
    Flight::auth_middleware()->authorizeRoles([Roles::ADMIN]);
    Flight::json(Flight::habitCompletionService()->get_all());
});
?>