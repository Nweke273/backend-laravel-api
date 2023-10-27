<?php

namespace App\Http\Controllers\Api\V1;

use Illuminate\Http\Request;
use App\Services\DataSourceService;
use App\Http\Controllers\Controller;
use App\Http\Requests\DataSourceRequest;


class DataSourceController extends Controller
{
    private $dataSourceService;

    public function __construct(DataSourceService $dataSourceService)
    {
        $this->dataSourceService = $dataSourceService;
    }

    public function articles(DataSourceRequest $request)
    {
        $user = auth()->user();
        $searchParams = [
            'q' => $request->keyword ?? 'news',
            'from' => $request->from,
            'to' => $request->to,
        ];

        if ($request->author) {
            $searchParams['author'] = $request->author;
        } elseif ($user->authors->isNotEmpty()) {
            $searchParams['author'] = $user->authors->pluck('author')->toArray();
        }

        if ($request->category) {
            $searchParams['category'] = $request->category;
        } elseif ($user->categories->isNotEmpty()) {
            $searchParams['category'] = $user->categories->pluck('category')->toArray();
        }

        $sources = $user->sources->pluck('source')->toArray();

        $articles = $this->dataSourceService->fetchArticles(10, $searchParams, $sources);

        return $articles;
    }


    public function topHeadlines(Request $request)
    {
        return $this->dataSourceService->getTopHeadlines();
    }
}
