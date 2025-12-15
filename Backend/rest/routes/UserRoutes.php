<?php
require_once __DIR__ . '/../../data/Roles.php';

/**
 * @OA\Get(
 *     path="/users",
 *     tags={"users"},
 *     summary="Get all users - ADMIN ONLY",
 *     security={{"ApiKey": {}}},
 *     @OA\Response(
 *         response=200,
 *         description="Array of all users in the database"
 *     )
 * )
 */
Flight::route('GET /users', function() {
    Flight::auth_middleware()->authorizeRoles([Roles::ADMIN]);
    Flight::json(Flight::userService()->get_all());
});

/**
 * @OA\Get(
 *     path="/users/{id}",
 *     tags={"users"},
 *     summary="Get user by ID",
 *     security={{"ApiKey": {}}},
 *     @OA\Parameter(
 *         name="id",
 *         in="path",
 *         required=true,
 *         description="ID of the user",
 *         @OA\Schema(type="integer", example=1)
 *     ),
 *     @OA\Response(
 *         response=200,
 *         description="Returns the user with the given ID"
 *     )
 * )
 */
Flight::route('GET /users/@id', function($id) {
    $user = Flight::get('user');
    
    // Users can only view their own profile unless admin
    if ($user->role !== Roles::ADMIN && $user->id != $id) {
        Flight::halt(403, "Unauthorized");
    }
    
    Flight::json(Flight::userService()->get_by_id($id));
});

/**
 * @OA\Post(
 *     path="/users/register",
 *     tags={"users"},
 *     summary="Register a new user - PUBLIC",
 *     @OA\Response(
 *         response=200,
 *         description="User registered successfully"
 *     )
 * )
 */
Flight::route('POST /users/register', function() {
    $data = Flight::request()->data->getData();
    Flight::json(Flight::userService()->register_user($data));
});

/**
 * @OA\Get(
 *     path="/users/email/{email}",
 *     tags={"users"},
 *     summary="Get user by email - ADMIN ONLY",
 *     security={{"ApiKey": {}}},
 *     @OA\Parameter(
 *         name="email",
 *         in="path",
 *         required=true,
 *         description="Email address of the user",
 *         @OA\Schema(type="string", example="john@example.com")
 *     ),
 *     @OA\Response(
 *         response=200,
 *         description="Returns the user with the given email"
 *     )
 * )
 */
Flight::route('GET /users/email/@email', function($email) {
    Flight::auth_middleware()->authorizeRoles([Roles::ADMIN]);
    Flight::json(Flight::userService()->get_by_email($email));
});

/**
 * @OA\Get(
 *     path="/users/username/{username}",
 *     tags={"users"},
 *     summary="Get user by username - ADMIN ONLY",
 *     security={{"ApiKey": {}}},
 *     @OA\Parameter(
 *         name="username",
 *         in="path",
 *         required=true,
 *         description="Username of the user",
 *         @OA\Schema(type="string", example="john_doe")
 *     ),
 *     @OA\Response(
 *         response=200,
 *         description="Returns the user with the given username"
 *     )
 * )
 */
Flight::route('GET /users/username/@username', function($username) {
    Flight::auth_middleware()->authorizeRoles([Roles::ADMIN]);
    Flight::json(Flight::userService()->get_by_username($username));
});

/**
 * @OA\Put(
 *     path="/users/{id}",
 *     tags={"users"},
 *     summary="Update user profile - OWNER ONLY",
 *     security={{"ApiKey": {}}},
 *     @OA\Parameter(
 *         name="id",
 *         in="path",
 *         required=true,
 *         description="ID of the user",
 *         @OA\Schema(type="integer", example=1)
 *     ),
 *     @OA\RequestBody(
 *         required=true,
 *         @OA\JsonContent(
 *             @OA\Property(property="username", type="string", example="new_username"),
 *             @OA\Property(property="email", type="string", example="new@email.com"),
 *             @OA\Property(property="password", type="string", example="newpassword123")
 *         )
 *     ),
 *     @OA\Response(
 *         response=200,
 *         description="User updated successfully"
 *     )
 * )
 */
Flight::route('PUT /users/@id', function($id) {
    $current_user = Flight::get('user');
    if ($current_user->role !== Roles::ADMIN && $current_user->id != $id) {
        Flight::halt(403, "Unauthorized");
    }
    $data = Flight::request()->data->getData();
    
    if (isset($data['password']) && !empty($data['password'])) {
        $data['password_hash'] = password_hash($data['password'], PASSWORD_DEFAULT);
        unset($data['password']);
    }
    
    Flight::json(Flight::userService()->update($data, $id));
});

/**
 * @OA\Delete(
 *     path="/users/{id}",
 *     tags={"users"},
 *     summary="Delete user account - OWNER OR ADMIN",
 *     security={{"ApiKey": {}}},
 *     @OA\Parameter(
 *         name="id",
 *         in="path",
 *         required=true,
 *         description="ID of the user",
 *         @OA\Schema(type="integer", example=1)
 *     ),
 *     @OA\Response(
 *         response=200,
 *         description="User deleted successfully"
 *     )
 * )
 */
Flight::route('DELETE /users/@id', function($id) {
    $current_user = Flight::get('user');

    if ($current_user->role !== Roles::ADMIN && $current_user->id != $id) {
        Flight::halt(403, "Unauthorized");
    }
    $data = Flight::request()->data->getData();
    Flight::userService()->delete($id);
    Flight::json(['message' => 'User account deleted successfully']);
});
?>