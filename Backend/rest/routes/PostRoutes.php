<?php
/**
 * @OA\Get(
 *     path="/posts",
 *     tags={"posts"},
 *     summary="Get all posts",
 *     @OA\Response(
 *         response=200,
 *         description="Array of all posts in the database"
 *     )
 * )
 */
Flight::route('GET /posts', function() {
    Flight::json(Flight::postService()->get_all());
});

/**
 * @OA\Get(
 *     path="/posts/{id}",
 *     tags={"posts"},
 *     summary="Get post by ID",
 *     @OA\Parameter(
 *         name="id",
 *         in="path",
 *         required=true,
 *         description="ID of the post",
 *         @OA\Schema(type="integer", example=1)
 *     ),
 *     @OA\Response(
 *         response=200,
 *         description="Returns the post with the given ID"
 *     )
 * )
 */
Flight::route('GET /posts/@id', function($id) {
    Flight::json(Flight::postService()->get_by_id($id));
});

/**
 * @OA\Post(
 *     path="/posts",
 *     tags={"posts"},
 *     summary="Create a new post",
 *     @OA\RequestBody(
 *         required=true,
 *         @OA\JsonContent(
 *             required={"title", "content", "user_id"},
 *             @OA\Property(property="title", type="string", example="My First Post"),
 *             @OA\Property(property="content", type="string", example="This is the content of my post"),
 *             @OA\Property(property="user_id", type="integer", example=1)
 *         )
 *     ),
 *     @OA\Response(
 *         response=200,
 *         description="Post created successfully"
 *     )
 * )
 */
Flight::route('POST /posts', function() {
    $data = Flight::request()->data->getData();
    Flight::json(Flight::postService()->create_post($data));
});

/**
 * @OA\Get(
 *     path="/posts/user/{user_id}",
 *     tags={"posts"},
 *     summary="Get posts by user ID",
 *     @OA\Parameter(
 *         name="user_id",
 *         in="path",
 *         required=true,
 *         description="ID of the user",
 *         @OA\Schema(type="integer", example=1)
 *     ),
 *     @OA\Response(
 *         response=200,
 *         description="Array of posts for the specified user"
 *     )
 * )
 */
Flight::route('GET /posts/user/@user_id', function($user_id) {
    Flight::json(Flight::postService()->get_by_user_id($user_id));
});

/**
 * @OA\Get(
 *     path="/posts/search",
 *     tags={"posts"},
 *     summary="Search posts",
 *     @OA\Parameter(
 *         name="q",
 *         in="query",
 *         required=false,
 *         description="Search term",
 *         @OA\Schema(type="string", example="habit")
 *     ),
 *     @OA\Response(
 *         response=200,
 *         description="Array of posts matching search term"
 *     )
 * )
 */
Flight::route('GET /posts/search', function() {
    $search_term = Flight::request()->query['q'] ?? '';
    Flight::json(Flight::postService()->search_posts($search_term));
});

/**
 * @OA\Put(
 *     path="/posts/{id}",
 *     tags={"posts"},
 *     summary="Update a post by ID",
 *     @OA\Parameter(
 *         name="id",
 *         in="path",
 *         required=true,
 *         description="ID of the post",
 *         @OA\Schema(type="integer", example=1)
 *     ),
 *     @OA\RequestBody(
 *         required=true,
 *         @OA\JsonContent(
 *             @OA\Property(property="title", type="string", example="Updated post title"),
 *             @OA\Property(property="content", type="string", example="Updated post content")
 *         )
 *     ),
 *     @OA\Response(
 *         response=200,
 *         description="Post updated successfully"
 *     )
 * )
 */
Flight::route('PUT /posts/@id', function($id) {
    $data = Flight::request()->data->getData();
    Flight::json(Flight::postService()->update($data, $id));
});

/**
 * @OA\Delete(
 *     path="/posts/{id}",
 *     tags={"posts"},
 *     summary="Delete a post by ID",
 *     @OA\Parameter(
 *         name="id",
 *         in="path",
 *         required=true,
 *         description="ID of the post",
 *         @OA\Schema(type="integer", example=1)
 *     ),
 *     @OA\Response(
 *         response=200,
 *         description="Post deleted successfully"
 *     )
 * )
 */
Flight::route('DELETE /posts/@id', function($id) {
    Flight::postService()->delete($id);
    Flight::json(['message' => 'Post deleted successfully']);
});
?>