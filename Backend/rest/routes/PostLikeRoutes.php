<?php
require_once __DIR__ . '/../../data/Roles.php';

/**
 * @OA\Get(
 *     path="/likes",
 *     tags={"likes"},
 *     summary="Get all post likes - ADMIN ONLY",
 *     security={{"ApiKey": {}}},
 *     @OA\Response(
 *         response=200,
 *         description="Array of all post likes"
 *     )
 * )
 */
Flight::route('GET /likes', function() {
    Flight::auth_middleware()->authorizeRoles([Roles::ADMIN]);
    Flight::json(Flight::postLikeService()->get_all());
});

/**
 * @OA\Get(
 *     path="/likes/post/{post_id}",
 *     tags={"likes"},
 *     summary="Get likes count for post - PUBLIC",
 *     @OA\Parameter(
 *         name="post_id",
 *         in="path",
 *         required=true,
 *         description="ID of the post",
 *         @OA\Schema(type="integer", example=1)
 *     ),
 *     @OA\Response(
 *         response=200,
 *         description="Likes count for the specified post"
 *     )
 * )
 */
Flight::route('GET /likes/post/@post_id', function($post_id) {
    // This is public
    $count = Flight::postLikeService()->get_likes_count($post_id);
    Flight::json(['post_id' => $post_id, 'likes_count' => $count]);
});

/**
 * @OA\Get(
 *     path="/likes/post/{post_id}/user/{user_id}",
 *     tags={"likes"},
 *     summary="Check if user liked post - AUTHENTICATED USERS",
 *     security={{"ApiKey": {}}},
 *     @OA\Parameter(
 *         name="post_id",
 *         in="path",
 *         required=true,
 *         description="ID of the post",
 *         @OA\Schema(type="integer", example=1)
 *     ),
 *     @OA\Parameter(
 *         name="user_id",
 *         in="path",
 *         required=true,
 *         description="ID of the user",
 *         @OA\Schema(type="integer", example=1)
 *     ),
 *     @OA\Response(
 *         response=200,
 *         description="Check if user liked the post"
 *     )
 * )
 */
Flight::route('GET /likes/post/@post_id/user/@user_id', function($post_id, $user_id) {
    $current_user = Flight::get('user');
    if ($current_user->id != $user_id && $current_user->role !== Roles::ADMIN) {
        Flight::halt(403, "Unauthorized");
    }
    
    $user_like = Flight::postLikeService()->get_user_like($post_id, $user_id);
    Flight::json(['user_liked' => !empty($user_like)]);
});

/**
 * @OA\Post(
 *     path="/likes/toggle",
 *     tags={"likes"},
 *     summary="Toggle like/unlike for post - AUTHENTICATED USERS",
 *     security={{"ApiKey": {}}},
 *     @OA\RequestBody(
 *         required=true,
 *         @OA\JsonContent(
 *             required={"post_id"},
 *             @OA\Property(property="post_id", type="integer", example=1)
 *         )
 *     ),
 *     @OA\Response(
 *         response=200,
 *         description="Like toggled successfully"
 *     )
 * )
 */
Flight::route('POST /likes/toggle', function() {
    $current_user = Flight::get('user');
    $data = Flight::request()->data->getData();
    
    $post_id = $data['post_id'];
    $user_id = $current_user->id;
    
    $result = Flight::postLikeService()->toggle_like($post_id, $user_id);
    Flight::json($result);
});
?>