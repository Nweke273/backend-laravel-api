<?php

namespace App\Http\Controllers\Api\V1;

use App\Models\User;
use App\Models\Author;
use App\Models\Source;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

/**
 * @group User
 */

class UserController extends Controller
{
    public function updatePreferences(Request $request)
    {
        $user = auth()->user();
        if (!empty($request->sources)) {
            foreach ($request->sources as $source) {
                $user->sources->create(['source' => $source, 'user_id' => $user->id]);
            }
        }
        
        if (!empty($request->authors)) {
    
                $user->authors->create(['author' => $request->author, 'user_id' => $user->id]);
         
        
            if (!empty($request->categories)) {
                foreach ($request->categories as $category) {
                    $user->categories->create(['category' => $category, 'user_id' => $user->id]);
                }
            }
        }
        

        return response()->json(['status' => 'success', 'message' => 'Preferences updated successfully']);
    }

    public function getCategories()
    {
        return response()->json([
            "status" => "success",
            "message" => "user preferred categories succesfully retrieved",
            "data" => auth()->user()->categories
        ]);
    }

    public function getSources(User $user)
    {
        return response()->json([
            "status" => "success",
            "message" => "user preferred sources succesfully retrieved",
            "data" =>  auth()->user()->sources
        ]);
    }

    public function getAuthors()
    {
        return response()->json([
            "status" => "success",
            "message" => "user preferred authors succesfully retrieved",
            "data" => auth()->user()->authors
        ]);
    }
}
