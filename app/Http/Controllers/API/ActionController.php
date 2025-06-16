<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Admin\Controller;
use App\Models\Action;
use App\Models\Camera;
use Illuminate\Http\Request;

/**
 * @OA\Info(
 *     title="My API",
 *     version="1.0.0",
 *     description="API documentation for my Laravel application"
 * )
 */

class ActionController extends Controller
{
    /**
     * @OA\Post(
     *     path="/api/actions/create",
     *     summary="Create a new action",
     *     description="Create a new action with camera_id, animal_type, and image_base64",
     *     tags={"Actions"},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"camera_id", "animal_type", "image_base64"},
     *             @OA\Property(property="camera_id", type="integer", description="ID of the camera", example=1),
     *             @OA\Property(property="animal_type", type="integer", description="Animal type (0=Dog, 1=Pig, 2=Pigeon)", example=0),
     *             @OA\Property(property="image_base64", type="string", description="Base64 encoded image", example="iVBORw0KGgoAAAANSUhEUgAABAAAA...==")
     *         )
     *     ),
     *     @OA\Response(
     *         response=201,
     *         description="Action created successfully",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="Action successfully created"),
     *             @OA\Property(
     *                  property="data",
     *                  type="object",
     *                  @OA\Property(property="id", type="integer", example=1),
     *                  @OA\Property(property="camera_id", type="integer", example=1),
     *                  @OA\Property(property="animal_type", type="integer", example=0),
     *                  @OA\Property(property="image_base64", type="string", example="iVBORw0KGgoAAAANSUhEUgAABAAAA...=="),
     *                  @OA\Property(property="created_at", type="string", format="date-time", example="2023-05-20T10:00:00Z"),
     *                  @OA\Property(property="updated_at", type="string", format="date-time", example="2023-05-20T10:00:00Z")
     *              )
     *         )
     *     ),
     *     @OA\Response(
     *         response=400,
     *         description="Bad request, validation failed",
     *     ),
     *     @OA\Response(
     *         response=500,
     *         description="Internal server error",
     *     )
     * )
     */
    public function create(Request $request)
    {

        $request->validate([
            'camera_id' => 'required|exists:cameras,id',
            'animal_type' => 'required|integer|in:0,1,2',
            'image_base64' => 'required|string',
        ]);


        $action = new Action();
        $action->camera_id = $request->camera_id;
        $action->animal_type = $request->animal_type;
        $action->image_base64 = $request->image_base64;
        $action->save();
        $camera = Camera::find($request->camera_id);
        $camera->state = 1;
        $camera->save();
        return response()->json([
            'message' => 'Action successfully created',
            'data' => $action
        ], 201); // 201 Created
    }
    /**
     * @OA\Get(
     *     path="/api/actions/get/{id}",
     *     summary="Get Action by ID",
     *     tags={"Actions"},
     *     description="Retrieve an action by its ID",
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         @OA\Schema(
     *             type="integer",
     *             example=1
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Action retrieved successfully",
     *         @OA\JsonContent(type="object", properties={
     *             @OA\Property(property="id", type="integer", example=1),
     *             @OA\Property(property="image_base64", type="string", example="data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAAlgAAAJYCAYAAAC+ZpjcAAAACXBIWXMAAA7EAAAOxAGVKw4bAAAgAElEQVR4nOy93a9uT3Ie9PSa7T1Hx0eTkZnMOMNoZE3MaBhbI9uEMODgHLEPwkIhQgIhghASd0iICyuKUCQQl1zlij8AIRAB8SGEfEP0czR27EzioBiifOGLKDH22B5b9njm+Pz27N8+q7l4u3o99XRVr/W++93mxi0dnXevj+pa1VVPV39UdcF1ygJgDX7PntNrS/s7+21/R+8cvZfxy89l7y2Ta9m70d96PeNP34nuR+WIfGe0Mj6P/K00Iz1g2rN3jshKaUV6uCer7J0Zj7P393jU8oeyynnUcq6sou/Zsw8Ef/N7l8oqup89z7xFtJWPP5TVH8oqov+Hsvr/WVZlwuDZ5e7u9VIaxVqBUoAPPvhaVPHR4gT05s3rThc4THvPWQEAvHnzehAk0d9zyPYUcXnz5rXStntH+Jx1Rk7uQp/5VN4OG4/KRuS+p4Cz6+tBuWeKP23/Ce0jdHeNh9u0VuBnfuZrw/clv7mEslLazZaidy6S1aRNI907S1aJ3Gd8271Dstrh3T27wzf/jQnvmZ6dLSujn7TpXmeU3V8ArzPE954N6rVDfDf6ER8XyYrlTraUdbhnyeru7jUO4uNFsjqgj0c7c+Z9hu2ZHJV+VLf7joD3iMbwXnBvuK76KLzP+rhdWV0B25W++53YEvPJJZXVTXBx1vkedk5KORlKQIMZij5W63MdGTtwwpM6B3Yv80hDBdvhV3/z/5Hh8TNaIiVY5Z79tr+1E2ZQWkoJaWXfAbnPshocwuD52Xdk9WlHZqC9AFgVSDDqibZ1CHwJ76pvM0cq0s1OR5yfpZTOO8tR22um53atDyK4lHLISdiV1RltepGsGu9..."),
     *             @OA\Property(property="animal_type", type="string", example="Dog"),
     *             @OA\Property(property="created_at", type="string", format="date-time", example="2023-05-20T10:00:00Z")
     *         })
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Action not found",
     *     )
     * )
     */
    public function get($id)
    {
        $action = Action::find($id);

        if (!$action) {
            return response()->json(['message' => 'Action not found'], 404);
        }
        return response()->json([
            'data' => $action
        ], 201);
    }
}
