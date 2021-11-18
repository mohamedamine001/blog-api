<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;
/**
 * @OA\Info(
 *      version="1.0.0",
 *      title="Api MiniBlog APP Documentation",
 *      description="Implementation of Swagger with in Laravel",
 *      @OA\Contact(
 *          email="mohamed.amine.aloui@gmail.com"
 *      ),
 *      @OA\License(
 *          name="Apache 2.0",
 *          url="http://www.apache.org/licenses/LICENSE-2.0.html"
 *      )
 * )
 *
 * @OA\Server(
 *      url=L5_SWAGGER_CONST_HOST,
 *      description="Demo API Server"
 * )

 *
 *
 */
class Controller extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;

    /**
     * Gloabel Validation Errors -
     * It will get the object of validation errors and return in JSON
     */

    public function validationErrors($validationErrors) {
        return response()->json(["status" => "error", "validation_errors" => $validationErrors]);
    }
}
