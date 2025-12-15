<?php
require_once __DIR__ . '/../../data/Roles.php';

/**
 * @OA\Get(
 *     path="/habits",
 *     tags={"habits"},
 *     summary="Get all habits - ADMIN ONLY",
 *     security={{"ApiKey": {}}},
 *     @OA\Response(
 *         response=200,
 *         description="Array of all habits in the database"
 *     )
 * )
 */
Flight::route('GET /habits', function() {
    Flight::auth_middleware()->authorizeRoles([Roles::ADMIN]);
    Flight::json(Flight::habitService()->get_all());
});

/**
 * @OA\Get(
 *     path="/habits/{id}",
 *     tags={"habits"},
 *     summary="Get habit by ID - OWNER OR ADMIN",
 *     security={{"ApiKey": {}}},
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
    $current_user = Flight::get('user');
    $habit = Flight::habitService()->get_by_id($id);
    
    if (!$habit) {
        Flight::halt(404, "Habit not found");
    }
    
    if ($current_user->role !== Roles::ADMIN && $current_user->id != $habit['user_id']) {
        Flight::halt(403, "Unauthorized");
    }
    
    Flight::json($habit);
});

/**
 * @OA\Post(
 *     path="/habits",
 *     tags={"habits"},
 *     summary="Create a new habit - AUTHENTICATED USERS",
 *     security={{"ApiKey": {}}},
 *     @OA\RequestBody(
 *         required=true,
 *         @OA\JsonContent(
 *             required={"name"},
 *             @OA\Property(property="name", type="string", example="Exercise daily"),
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
    $current_user = Flight::get('user');
    $data = Flight::request()->data->getData();
    
    $data['user_id'] = $current_user->id;
    
    Flight::json(Flight::habitService()->create_habit($data));
});

/**
 * @OA\Get(
 *     path="/habits/user/{user_id}",
 *     tags={"habits"},
 *     summary="Get habits by user ID - OWNER OR ADMIN",
 *     security={{"ApiKey": {}}},
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
    $current_user = Flight::get('user');
    
    if ($current_user->role !== Roles::ADMIN && $current_user->id != $user_id) {
        Flight::halt(403, "Unauthorized");
    }
    
    Flight::json(Flight::habitService()->get_by_user_id($user_id));
});

/**
 * @OA\Get(
 *     path="/habits/user/{user_id}/category/{category}",
 *     tags={"habits"},
 *     summary="Get habits by user ID and category - OWNER OR ADMIN",
 *     security={{"ApiKey": {}}},
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
    $current_user = Flight::get('user');

    if ($current_user->role !== Roles::ADMIN && $current_user->id != $user_id) {
        Flight::halt(403, "Unauthorized");
    }
    
    Flight::json(Flight::habitService()->get_by_category($user_id, $category));
});

/**
 * @OA\Put(
 *     path="/habits/{id}",
 *     tags={"habits"},
 *     summary="Update a habit by ID - OWNER OR ADMIN",
 *     security={{"ApiKey": {}}},
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
    $current_user = Flight::get('user');
    $habit = Flight::habitService()->get_by_id($id);
    
    if (!$habit) {
        Flight::halt(404, "Habit not found");
    }
    if ($current_user->role !== Roles::ADMIN && $current_user->id != $habit['user_id']) {
        Flight::halt(403, "Unauthorized");
    }
    
    $data = Flight::request()->data->getData();
    Flight::json(Flight::habitService()->update($data, $id));
});

/**
 * @OA\Delete(
 *     path="/habits/{id}",
 *     tags={"habits"},
 *     summary="Delete a habit by ID - OWNER OR ADMIN",
 *     security={{"ApiKey": {}}},
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
    $current_user = Flight::get('user');
    $habit = Flight::habitService()->get_by_id($id);
    
    if (!$habit) {
        Flight::halt(404, "Habit not found");
    }
    if ($current_user->role !== Roles::ADMIN && $current_user->id != $habit['user_id']) {
        Flight::halt(403, "Unauthorized");
    }
    
    Flight::habitService()->delete($id);
    Flight::json(['message' => 'Habit deleted successfully']);
});
?>