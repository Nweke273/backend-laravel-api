<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class UtilityController extends Controller
{
    public function getSources()
    {
        $sources = [
            'nyt' => 'New York Times',
            'news_api' => 'News',
            'guardian' => 'The Guardian'
        ];

        return response()->json([
            'status' => 'success',
            'message' => 'categories retireved succesfully',
            'data' => $sources
        ]);
    }

    public function getCategories()
    {
        $categories = [
            'Technology',
            'Business',
            'Health',
            'Science',
            'Sports',
            'Entertainment',
            'Politics',
            'World News',
            'Environment',
            'Education',
        ];

        return response()->json([
            'status' => 'success',
            'message' => 'categories retireved succesfully',
            'data' => $categories
        ]);
    }
}
