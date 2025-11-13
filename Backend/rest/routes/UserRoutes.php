<?php
/**
 * @OA\Get(
 *     path="/users",
 *     tags={"users"},
 *     summary="Get all users",
 *     @OA\Response(
 *         response=200,
 *         description="Array of all users in the database"
 *     )
 * )
 */
Flight::route('GET /users', function() {
    Flight::json(Flight::userService()->get_all());
});

/**
 * @OA\Get(
 *     path="/users/{id}",
 *     tags={"users"},
 *     summary="Get user by ID",
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
    Flight::json(Flight::userService()->get_by_id($id));
});

/**
 * @OA\Post(
 *     path="/users/register",
 *     tags={"users"},
 *     summary="Register a new user",
 *     @OA\RequestBody(
 *         required=true,
 *         @OA\JsonContent(
 *             required={"username", "email", "password"},
 *             @OA\Property(property="username", type="string", example="john_doe"),
 *             @OA\Property(property="email", type="string", example="john@example.com"),
 *             @OA\Property(property="password", type="string", example="securepassword123"),
 *             @OA\Property(property="first_name", type="string", example="John"),
 *             @OA\Property(property="last_name", type="string", example="Doe")
 *         )
 *     ),
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
 *     summary="Get user by email",
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
    Flight::json(Flight::userService()->get_by_email($email));
});

/**
 * @OA\Get(
 *     path="/users/username/{username}",
 *     tags={"users"},
 *     summary="Get user by username",
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
    Flight::json(Flight::userService()->get_by_username($username));
});
?>