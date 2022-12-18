<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Post;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
use App\Traits\ApiTrait;
use App\Traits\ValidationTrait;

class PostController extends Controller
{
    use ApiTrait, ValidationTrait;
    protected $user;

    public function __construct()
    {
        $this->user = auth('sanctum')->user();
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $posts = Post::getDataPostByUserAccess($this->user);

        return $this->responseSuccess([
                    'success' => true,
                    'data' => Post::modifyValueDataPost($posts)
                ], 'Fetch all posts');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), $this->PostValidation());

        if ($validator->fails()) {
            return $this->responseError([
                'success' => false,
                'data' => []
            ], 400, $validator->errors()->first());
        }

        $post = Post::create([
            'title' => $request->get('title'),
            'content' => $request->get('content'),
            'status' => $request->get('status'),
            'user_id' => $this->user->id
        ]);

        return  $this->responseSuccess([
                    'success' => true,
                    'data' => Post::modifyValueDataPost($post)
                ], 'Post created successfully.');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Post  $post
     * @return \Illuminate\Http\JsonResponse
     */
    public function show($post)
    {
        $posts = Post::getDataPostByUserAccess($this->user, $post);

        $msg = "Data post found";
        if(empty($posts) || is_null($posts)) {
            $msg = "Data post not found";
            $posts = [];
        }

        return  $this->responseSuccess([
                    'success' => true,
                    'data' => Post::modifyValueDataPost($posts)
                ], $msg);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Post  $post
     * @return \Illuminate\Http\JsonResponse
     */
    public function update(Request $request, $post)
    {
        $postData = Post::getDataPostByUserAccess($this->user, $post);

        if(empty($postData)) {
            return $this->responseError([], 401, "You're not allowed to do this action");
        } else {
            $validator = Validator::make($request->all(), $this->PostValidation());

            if ($validator->fails()) {
                return $this->responseError([
                    'success' => false,
                    'data' => []
                ], 400, $validator->errors()->first());
            }

            $postData->update([
                'title' => $request->get('title'),
                'content' => $request->get('content'),
                'status' => $request->get('status')
            ]);

            return  $this->responseSuccess([
                        'success' => true,
                        'data' => Post::modifyValueDataPost($postData)
                    ], 'Post updated successfully.');
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Post  $post
     * @return \Illuminate\Http\JsonResponse
     */
    public function destroy($post)
    {
        $postData = Post::getDataPostByUserAccess($this->user, $post);

        if(empty($postData)) {
            return $this->responseError([], 401, "You're not allowed to do this action");
        } else{
            $postData->delete();

            return  $this->responseSuccess([
                        'success' => true,
                        'data' => []
                    ], 'Post deleted successfully.');
        }
    }
}
