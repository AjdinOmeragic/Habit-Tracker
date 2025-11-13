<?php
/**
 * @OA\Get(
 *     path="/completions/habit/{habit_id}",
 *     tags={"completions"},
 *     summary="Get completions by habit ID",
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
    Flight::json(Flight::habitCompletionService()->get_by_habit_id($habit_id));
});

/**
 * @OA\Post(
 *     path="/completions",
 *     tags={"completions"},
 *     summary="Mark a habit as completed",
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
    $data = Flight::request()->data->getData();
    Flight::json(Flight::habitCompletionService()->mark_completed($data));
});

/**
 * @OA\Get(
 *     path="/completions/check/{habit_id}/{date}",
 *     tags={"completions"},
 *     summary="Check if habit is completed on specific date",
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
    $isCompleted = Flight::habitCompletionService()->is_completed_on_date($habit_id, $date);
    Flight::json(['completed' => $isCompleted]);
});

/**
 * @OA\Get(
 *     path="/habit-completions",
 *     tags={"completions"},
 *     summary="Get all habit completions",
 *     @OA\Response(
 *         response=200,
 *         description="Array of all habit completions"
 *     )
 * )
 */
Flight::route('GET /habit-completions', function() {
    Flight::json(Flight::habitCompletionService()->get_all());
});
?>