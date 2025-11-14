<?php
/**
 * @OA\Get(
 *     path="/habits",
 *     tags={"habits"},
 *     summary="Get all habits",
 *     @OA\Response(
 *         response=200,
 *         description="Array of all habits in the database"
 *     )
 * )
 */
Flight::route('GET /habits', function() {
    Flight::json(Flight::habitService()->get_all());
});

/**
 * @OA\Get(
 *     path="/habits/{id}",
 *     tags={"habits"},
 *     summary="Get habit by ID",
 *     @OA\Parameter(
 *         name="id",
 *         in="path",
 *         required=true,
 *         description="ID of the habit",
 *         @OA\Schema(type="integer", example=1)
 *     ),
 *     @OA\Response(
 *         response=200,
 *         description="Returns the habit with the given ID"
 *     )
 * )
 */
Flight::route('GET /habits/@id', function($id) {
    Flight::json(Flight::habitService()->get_by_id($id));
});

/**
 * @OA\Post(
 *     path="/habits",
 *     tags={"habits"},
 *     summary="Create a new habit",
 *     @OA\RequestBody(
 *         required=true,
 *         @OA\JsonContent(
 *             required={"name", "user_id"},
 *             @OA\Property(property="name", type="string", example="Exercise daily"),
 *             @OA\Property(property="user_id", type="integer", example=1),
 *             @OA\Property(property="category", type="string", example="health"),
 *             @OA\Property(property="description", type="string", example="30 minutes of exercise")
 *         )
 *     ),
 *     @OA\Response(
 *         response=200,
 *         description="Habit created successfully"
 *     )
 * )
 */
Flight::route('POST /habits', function() {
    $data = Flight::request()->data->getData();
    Flight::json(Flight::habitService()->create_habit($data));
});

/**
 * @OA\Get(
 *     path="/habits/user/{user_id}",
 *     tags={"habits"},
 *     summary="Get habits by user ID",
 *     @OA\Parameter(
 *         name="user_id",
 *         in="path",
 *         required=true,
 *         description="ID of the user",
 *         @OA\Schema(type="integer", example=1)
 *     ),
 *     @OA\Response(
 *         response=200,
 *         description="Array of habits for the specified user"
 *     )
 * )
 */
Flight::route('GET /habits/user/@user_id', function($user_id) {
    Flight::json(Flight::habitService()->get_by_user_id($user_id));
});

/**
 * @OA\Get(
 *     path="/habits/user/{user_id}/category/{category}",
 *     tags={"habits"},
 *     summary="Get habits by user ID and category",
 *     @OA\Parameter(
 *         name="user_id",
 *         in="path",
 *         required=true,
 *         description="ID of the user",
 *         @OA\Schema(type="integer", example=1)
 *     ),
 *     @OA\Parameter(
 *         name="category",
 *         in="path",
 *         required=true,
 *         description="Category of habits",
 *         @OA\Schema(type="string", example="health")
 *     ),
 *     @OA\Response(
 *         response=200,
 *         description="Array of habits for the specified user and category"
 *     )
 * )
 */
Flight::route('GET /habits/user/@user_id/category/@category', function($user_id, $category) {
    Flight::json(Flight::habitService()->get_by_category($user_id, $category));
});

/**
 * @OA\Put(
 *     path="/habits/{id}",
 *     tags={"habits"},
 *     summary="Update a habit by ID",
 *     @OA\Parameter(
 *         name="id",
 *         in="path",
 *         required=true,
 *         description="ID of the habit",
 *         @OA\Schema(type="integer", example=1)
 *     ),
 *     @OA\RequestBody(
 *         required=true,
 *         @OA\JsonContent(
 *             @OA\Property(property="name", type="string", example="Updated habit name"),
 *             @OA\Property(property="category", type="string", example="updated-category"),
 *             @OA\Property(property="description", type="string", example="Updated description")
 *         )
 *     ),
 *     @OA\Response(
 *         response=200,
 *         description="Habit updated successfully"
 *     )
 * )
 */
Flight::route('PUT /habits/@id', function($id) {
    $data = Flight::request()->data->getData();
    Flight::json(Flight::habitService()->update($data, $id));
});

/**
 * @OA\Delete(
 *     path="/habits/{id}",
 *     tags={"habits"},
 *     summary="Delete a habit by ID",
 *     @OA\Parameter(
 *         name="id",
 *         in="path",
 *         required=true,
 *         description="ID of the habit",
 *         @OA\Schema(type="integer", example=1)
 *     ),
 *     @OA\Response(
 *         response=200,
 *         description="Habit deleted successfully"
 *     )
 * )
 */
Flight::route('DELETE /habits/@id', function($id) {
    Flight::habitService()->delete($id);
    Flight::json(['message' => 'Habit deleted successfully']);
});
?>