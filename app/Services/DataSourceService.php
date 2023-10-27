<?php

namespace App\Services;

use GuzzleHttp\Client;
use Illuminate\Support\Facades\Log;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Pagination\Paginator;

use GuzzleHttp\Exception\GuzzleException;

class dataSourceService
{
    private $client;
    private $message;
    private $newsApiUrl;
    private $guardianApiUrl;
    private $newYorkTimesApiUrl;
    private $newsApiKey;
    private $guardianApiKey;
    private $newYorkTimesApiKey;

    public function __construct()
    {
        $this->client = new Client();
        $this->message = "Request successful. Data retrieved";
        $this->newsApiUrl = config('app.news_api_url');
        $this->guardianApiUrl = config('guardian_api_url');
        $this->newYorkTimesApiUrl = config('new_york_times_api_url');
        $this->newsApiKey = config('services.newsApi.api_key');
        $this->guardianApiKey = config('services.guardian.api_key');
        $this->newYorkTimesApiKey = config('services.new_york_times.api_key');
    }

    public function fetchArticles($perPage = 10, $queryParameters = [], $sources = [])
    {
        $client = new Client();
        $apiUrls = [];

        if (!in_array('nyt', $sources) && !in_array('news-api', $sources) && !in_array('guardian', $sources)) {
            $apiUrls[] = $this->newYorkTimesApiUrl . $this->newYorkTimesApiKey;
            $apiUrls[] = $this->newsApiUrl . $this->newsApiKey;
            $apiUrls[] = $this->guardianApiUrl . $this->guardianApiKey;
        } else {
            if (in_array('nyt', $sources)) {
                $apiUrls[] = $this->newYorkTimesApiUrl . $this->newYorkTimesApiKey;
            }

            if (in_array('news-api', $sources)) {
                $apiUrls[] = $this->newsApiUrl . $this->newsApiKey;
            }

            if (in_array('guardian', $sources)) {
                $apiUrls[] = $this->guardianApiUrl . $this->guardianApiKey;
            }
        }


        $combinedData = [];

        foreach ($apiUrls as $apiUrl) {
            try {
                $apiUrl .= '&' . http_build_query(array_merge(['pageSize' => $perPage], $queryParameters));

                $response = $client->get($apiUrl);
                $responseData = json_decode($response->getBody(), true);

                if (isset($responseData['articles'])) {
                    $combinedData = array_merge($combinedData, $responseData['articles']);
                } elseif (isset($responseData['response']['docs'])) {
                    $combinedData = array_merge($combinedData, $responseData['response']['docs']);
                } elseif (isset($responseData['response']['results'])) {
                    $combinedData = array_merge($combinedData, $responseData['response']['results']);
                } else {
                }
            } catch (\GuzzleHttp\Exception\ClientException $e) {
                return response()->json([
                    'status' => 'error',
                    'message' => $e->getMessage()
                ]);
            }
        }

        $perPage = max((int) $perPage, 10);

        $page = (int) Paginator::resolveCurrentPage('page');
        $items = collect($combinedData);
        $paginatedData = new LengthAwarePaginator(
            $items->forPage($page, $perPage),
            $items->count(),
            $perPage,
            $page,
            ['path' => Paginator::resolveCurrentPath()]
        );

        return [
            'status' => "success",
            'message' => $this->message,
            'articles' => $paginatedData,
        ];
    }

    public function getTopHeadlines()
    {
        $query = [
            'apiKey' => config('services.newsApi.api_key'),
            'sources' => 'google-news',
        ];
        try {
            $response = $this->client->get('https://newsapi.org/v2/top-headlines?pageSize=10' . 'top-headlines', [
                'query' => $query
            ]);

            $statusCode = $response->getStatusCode();
            $responseContent = json_decode($response->getBody(), true);

            if ($statusCode === 200) {
                $status = 'success';

                return [
                    'status' => $status,
                    'message' => $this->message,
                    'articles' => $responseContent,
                ];
            } else {
                $error = "Received a $statusCode status code. Response: " . json_encode($responseContent);
                return [
                    'status' => 'error',
                    'message' => $error,
                ];
            }
        } catch (GuzzleException $e) {
            Log::error($e->getMessage());
            return [
                'status' => 'error',
                'message' => $e->getMessage(),
            ];
        }
    }
}
