<?php

namespace App\Http\Controllers;

use Exception;
use Facade\FlareClient\Http\Exceptions\NotFound;
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class UserController extends Controller
{
     /**
     * Register User
     *
     * @param Request $request
     * @return void
     */

     /**
     * @OA\Post(
     *      path="/user/register",
     *      operationId="userRegistration",
     *      tags={"Register"},

     *      summary="Register a new user",
     *      description="Register a new user",
     *      @OA\Response(
     *          response=201,
     *          description="Successful operation",
     *          @OA\MediaType(
     *           mediaType="application/json",
     *      )
     *      ),
     *      @OA\Response(
     *          response=200,
     *          description="Validation Errors : email already taken or field error or missed",
     *      ),
     * @OA\Response(
     *      response=404,
     *      description="not found"
     *   ),
     *  )
     */

    public function register(Request $request) {

        $validator = Validator::make($request->all(), [
            "first_name" => "required",
            "last_name" => "required",
            "email" => "required|email|unique:users,email",
            "password" => "required|min:3",
           
        ]);

        if($validator->fails()) {
            return $this->validationErrors($validator->errors());
        }

        $user = User::create([
            'first_name' => $request->first_name,
            'last_name' => $request->last_name,
            'email' => $request->email,
            'password' => Hash::make($request->password)
        ]);

        return response()->json(["status" => "success", "error" => false, "message" => "Success! User registered."], 201);
    }

     /**
     * User Login
     *
     * @param Request $request
     * @return void
     */

     /**
     * @OA\Post(
     *      path="/user",
     *      operationId="userLogin",
     *      tags={"Login"},

     *      summary="User Login",
     *      description="User login by email and password",
     *      @OA\Response(
     *          response=200,
     *          description="LoggedIn",
     *          @OA\MediaType(
     *           mediaType="application/json",
     *      )
     *      ),
     *      
     *
     * @OA\Response(
     *      response=404,
     *      description="Not found or Login Failed : Invalid Credentials"
     *   ),
     *  )
     */

    public function login(Request $request) {

        $validator = Validator::make($request->all(), [
            "email" => "required|email",
            "password" => "required|min:3"
        ]);

        if($validator->fails()) {
            return $this->validationErrors($validator->errors());
        }

        try {
            if(Auth::attempt(['email' => $request->email, 'password' => $request->password])) {
                $user   = Auth::user();
                $token  = $user->createToken('token')->accessToken;
                $id     = $user->id;
                $first_name     = $user->first_name;
                return response()->json(
                    [
                        "status"  => "success",
                        "error"   => false,
                        "message" => "Success! you are logged in.",
                        "token"   => $token,
                        "id"      => $id,
                        "first_name" => $first_name
                    ]
                );
            }
            return response()->json(["status" => "failed", "message" => "Failed! invalid credentials."], 404);
        }
        catch(Exception $e) {
            return response()->json(["status" => "failed", "message" => $e->getMessage()], 404);
        }
    }

    /**
     * Logged User Data Using Auth Token
     *
     * @return void
     */

     /**
     * @OA\Get(
     *      path="/user",
     *      operationId="getLoggedInUserData",
     *      tags={"User"},

     *      summary="Logged User Data Using Auth Token",
     *      description="Returns Logged in user data using Auth Token",
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
    public function user() {
        try {
            $user = Auth::user();
            return response()->json(["status" => "success", "error" => false, "data" => $user], 200);
        }
        catch(NotFoundHttpException $exception) {
            return response()->json(["status" => "failed", "error" => $exception], 401);
        }
    }

    /**
    * Logout Auth User
    *
    * @param Request $request
    * @return void
    */

    /**
     * @OA\Post(
     *      path="/user/logout",
     *      operationId="userLogout",
     *      tags={"logout"},

     *      summary="User Logout",
     *      description="Logout User and revoke assigned Token",
     *      @OA\Response(
     *          response=200,
     *          description="Successful operation : User Logged out",
     *          @OA\MediaType(
     *           mediaType="application/json",
     *      )
     *      ),
     *      @OA\Response(
     *          response=403,
     *          description="User already logged-out"
     *      ),
     *  )
     */
    public function logout() {

        if(Auth::check()) {
            Auth::user()->token()->revoke();
            return response()->json(["status" => "success", "error" => false, "message" => "Success! You are logged out."], 200);
        }
        return response()->json(["status" => "failed", "error" => true, "message" => "Failed! You are already logged out."], 403);
    }
}
