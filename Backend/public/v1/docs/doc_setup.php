<?php
/**
 * @OA\Info(
 *     version="1.0.0",
 *     title="Habit Tracker API",
 *     description="API for tracking habits, completions, posts, and social features",
 *     @OA\Contact(
 *         email="ajdin.omeragic.pl@gmail.com",
 *         name="Habit Tracker Team"
 *     )
 * )
 */

/**
 * @OA\Server(
 *     url="http://localhost/habittracker/Backend",
 *     description="Local development server"
 * )
 */

/**
 * @OA\SecurityScheme(
 *     securityScheme="ApiKeyAuth",
 *     type="apiKey",
 *     in="header",
 *     name="Authorization"
 * )
 */
?>