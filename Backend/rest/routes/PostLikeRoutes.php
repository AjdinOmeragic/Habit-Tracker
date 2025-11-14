<?php
/**
 * @OA\Get(
 *     path="/likes",
 *     tags={"likes"},
 *     summary="Get all post likes",
 *     @OA\Response(
 *         response=200,
 *         description="Array of all post likes"
 *     )
 * )
 */
Flight::route('GET /likes', function() {
    Flight::json(Flight::postLikeService()->get_all());
});

/**
 * @OA\Get(
 *     path="/likes/post/{post_id}",
 *     tags={"likes"},
 *     summary="Get likes count for post",
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
    $count = Flight::postLikeService()->get_likes_count($post_id);
    Flight::json(['post_id' => $post_id, 'likes_count' => $count]);
});

/**
 * @OA\Get(
 *     path="/likes/post/{post_id}/user/{user_id}",
 *     tags={"likes"},
 *     summary="Check if user liked post",
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
    $user_like = Flight::postLikeService()->get_user_like($post_id, $user_id);
    Flight::json(['user_liked' => !empty($user_like)]);
});

/**
 * @OA\Post(
 *     path="/likes/toggle",
 *     tags={"likes"},
 *     summary="Toggle like/unlike for post",
 *     @OA\RequestBody(
 *         required=true,
 *         @OA\JsonContent(
 *             required={"post_id", "user_id"},
 *             @OA\Property(property="post_id", type="integer", example=1),
 *             @OA\Property(property="user_id", type="integer", example=1)
 *         )
 *     ),
 *     @OA\Response(
 *         response=200,
 *         description="Like toggled successfully"
 *     )
 * )
 */
Flight::route('POST /likes/toggle', function() {
    $data = Flight::request()->data->getData();
    $post_id = $data['post_id'];
    $user_id = $data['user_id'];
    $result = Flight::postLikeService()->toggle_like($post_id, $user_id);
    Flight::json($result);
});
?>