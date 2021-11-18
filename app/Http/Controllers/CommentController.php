<?php

namespace App\Http\Controllers;

use Exception;
use Facade\FlareClient\Http\Exceptions\NotFound;
use Illuminate\Http\Request;
use App\Models\Comment;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class CommentController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    /**
     * @OA\Get(
     *      path="/user/comments/post_id={post_id}",
     *      operationId="getCommentsByPostId",
     *      tags={"Comments"},

     *      summary="Get List Of Comments by post id",
     *      description="Returns all comment By post Id",
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
    public function index(Request $request)
    {
       //$comments = Auth::user()->comments;
         $comments = Comment::where('post_id', $request->post_id)
                              ->with('user')
                              ->orderBy('id', 'desc')
                              ->get();
        
        return response()->json(["status" => "success", "error" => false, "count" => count($comments), "data" => $comments],200);
    
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
     *      path="/user/comments",
     *      operationId="addingNewComment",
     *      tags={"addComment"},

     *      summary="Adding Comment",
     *      description="Adding a new Comment",
     *      @OA\Response(
     *          response=201,
     *          description="Comment Added Successfully",
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
            $comment = Comment::create([
                "content" => $request->content,
                "post_id" => $request->post_id,
                "user_id" => Auth::user()->id
            ]);
            return response()->json(["status" => "success", "error" => false, "message" => "Success! comment added."], 201);
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
     *      path="/user/comments/{id}",
     *      operationId="getCommentByID",
     *      tags={"Comment"},

     *      summary="Get Comment by ID",
     *      description="Returns Comment by its id",
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
        $comment = Comment::with('user', 'post')->findOrFail($id);

        if($comment) {
            return response()->json(["status" => "success", "error" => false, "data" => $comment], 200);
        }
        return response()->json(["status" => "failed", "error" => true, "message" => "Failed! no comment found."], 404);
  
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
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */

     /**
     * @OA\Delete(
     *      path="/user/comments/{id}",
     *      operationId="deleteComment",
     *      tags={"Comment"},

     *      summary="Delete Comment",
     *      description="Delete Comment By ID",
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
        $comment = Auth::user()->comments->find($id);
        if($comment) {
            $comment->delete();
            return response()->json(["status" => "success", "error" => false, "message" => "Success! comment deleted."], 200);
        }
        return response()->json(["status" => "failed", "error" => true, "message" => "Failed no comment found."], 404);

    }
}
