<?php

namespace App\Http\Controllers;

use Exception;
use Facade\FlareClient\Http\Exceptions\NotFound;
use Illuminate\Http\Request;
use App\Models\Post;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class PostController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */

     /**
     * @OA\Get(
     *      path="/user/posts",
     *      operationId="getAllPosts",
     *      tags={"POSTS"},

     *      summary="Get List Of Posts",
     *      description="Returns all posts",
     *      @OA\Response(
     *          response=200,
     *          description="Successful operation",
     *          @OA\MediaType(
     *           mediaType="application/json",
     *      )
     *      ),
     *      @OA\Response(
     *          response=401,
     *          description="Unauthenticated",
     *      ),
     *  )
     */
    public function index()
    {
        $posts = Post::orderBy('id', 'desc')->with('user')->get();
        return response()->json(["status" => "success", "error" => false, "count" => count($posts), "data" => $posts],200);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */

     /**
     * @OA\Post(
     *      path="/user/posts",
     *      operationId="addingNewPost",
     *      tags={"addPost"},

     *      summary="Adding Post",
     *      description="Adding a new Post",
     *      @OA\Response(
     *          response=201,
     *          description="Post Added Successfully",
     *          @OA\MediaType(
     *           mediaType="application/json",
     *      )
     *      ),
     *      @OA\Response(
     *          response=200,
     *          description="Validation Errors",
     *      ),
     *      @OA\Response(
     *          response=401,
     *          description="Unauthenticated",
     *      ),
     * @OA\Response(
     *      response=404,
     *      description="not found"
     *   ),
     *  )
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            "content" => "required"
        ]);
        if($validator->fails()) {
            return $this->validationErrors($validator->errors());
        }
        try {
            $post = Post::create([
               
                "content" => $request->content,
                "user_id" => Auth::user()->id
            ]);
            return response()->json(["status" => "success", "error" => false, "message" => "Success! post created."], 201);
        }
        catch(Exception $exception) {
            return response()->json(["status" => "failed", "error" => $exception->getMessage()], 404);
        }



    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */

     /**
     * @OA\Get(
     *      path="/user/posts/{id}",
     *      operationId="getPostByID",
     *      tags={"Post"},

     *      summary="Get Post by ID",
     *      description="Returns Post by its id",
     *      @OA\Response(
     *          response=200,
     *          description="Successful operation",
     *          @OA\MediaType(
     *           mediaType="application/json",
     *      )
     *      ),
     *       @OA\Parameter(
     *          name="id",
     *          in="path",
     *          required=true,
     *          @OA\Schema(
     *          type="integer"
     *       )
     *      ),
     *      @OA\Response(
     *          response=401,
     *          description="Unauthenticated",
     *      ),
     * @OA\Response(
     *      response=404,
     *      description="not found"
     *   ),
     *  )
     */
    public function show($id)
    {
        $post = Post::with('user', 'comments')->findOrFail($id);

        if($post) {
            return response()->json(["status" => "success", "error" => false, "data" => $post], 200);
        }
        return response()->json(["status" => "failed", "error" => true, "message" => "Failed! no post found."], 404);
  
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */

     /**
     * @OA\Put(
     *      path="/user/posts/{id}",
     *      operationId="updatePost",
     *      tags={"Post"},

     *      summary="Update Post",
     *      description="Post Updating",
     *      @OA\Response(
     *          response=201,
     *          description="Successful operation",
     *          @OA\MediaType(
     *           mediaType="application/json",
     *      )
     *      ),
     *      @OA\Parameter(
     *          name="id",
     *          in="path",
     *          required=true,
     *          @OA\Schema(
     *          type="integer"
     *       )
     *      ),
     *      @OA\Response(
     *          response=200,
     *          description="Validation Error",
     *          @OA\MediaType(
     *           mediaType="application/json",
     *      )
     *      ),
     *      @OA\Response(
     *          response=401,
     *          description="Unauthenticated",
     *      ),
     *      @OA\Response(
     *          response=403,
     *          description="Forbidden"
     *      ),
     * @OA\Response(
     *      response=404,
     *      description="not found"
     *   ),
     *  )
     */
    public function update(Request $request, $id)
    {
        $post = Auth::user()->posts->find($id);

        if($post) {
            $validator = Validator::make($request->all(), [
                'content' => 'required'
            ]);

            if($validator->fails()) {
                return $this->validationErrors($validator->errors());
            }

             $post['content'] = $request->content;

            $post->save();
            return response()->json(["status" => "success", "error" => false, "message" => "Success! post updated."], 201);
        }
        return response()->json(["status" => "failed", "error" => true, "message" => "Failed no post found."], 404);
 
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */

     /**
     * @OA\Delete(
     *      path="/user/posts/{id}",
     *      operationId="deletePost",
     *      tags={"Post"},

     *      summary="Delete Post",
     *      description="Delete Post By ID",
     *      @OA\Response(
     *          response=201,
     *          description="Successful operation",
     *          @OA\MediaType(
     *           mediaType="application/json",
     *      )
     *      ),
     *      @OA\Parameter(
     *          name="id",
     *          in="path",
     *          required=true,
     *          @OA\Schema(
     *          type="integer"
     *       )
     *      ),
     *      @OA\Response(
     *          response=401,
     *          description="Unauthenticated",
     *      ),
     * @OA\Response(
     *      response=404,
     *      description="not found"
     *   ),
     *  )
     */
    public function destroy($id)
    {
        $post = Auth::user()->posts->find($id);
        if($post) {
            $post->delete();
            return response()->json(["status" => "success", "error" => false, "message" => "Success! post deleted."], 200);
        }
        return response()->json(["status" => "failed", "error" => true, "message" => "Failed no post found."], 404);

    }
}
