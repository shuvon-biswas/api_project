<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Exception;
use Illuminate\Pagination\LengthAwarePaginator;

class ApiController extends Controller
{
    public function fetchDataApi()
    {
        $curl = curl_init();
        $url = 'https://api.publicapis.org/entries';

        curl_setopt_array(
            $curl,
            array(
                CURLOPT_URL => $url,
                CURLOPT_RETURNTRANSFER => true,
                CURLOPT_ENCODING => '',
                CURLOPT_MAXREDIRS => 10,
                CURLOPT_TIMEOUT => 0,
                CURLOPT_FOLLOWLOCATION => true,
                CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                CURLOPT_CUSTOMREQUEST => 'GET',
                // Ignore SSL verification (for development purposes only)
                CURLOPT_SSL_VERIFYPEER => false,
            )
        );

        $response = curl_exec($curl);

        // Check if cURL request was successful
        if ($response === false) {
            throw new Exception('cURL request failed: ' . curl_error($curl));
        }

        // Check if cURL encountered any errors
        if (curl_errno($curl)) {
            throw new Exception('cURL error: ' . curl_error($curl));
        }

        // Close the cURL session
        curl_close($curl);

        $responseData = json_decode($response)->entries;

        // Check if JSON decoding was successful
        if (json_last_error() !== JSON_ERROR_NONE) {
            throw new Exception('JSON decoding error: ' . json_last_error_msg());
        }

        return $responseData;
    }

    public function apiTest(Request $request)
    {
        // Fetch API data
        $responseData = $this->fetchDataApi();

        // Paginate the data
        $perPage = 20;
        $currentPage = $request->input('page', 1);
        $path = url('/admin/api-project'); // Set the correct base path
        $pagedData = array_slice($responseData, ($currentPage - 1) * $perPage, $perPage);
        $responseData = new LengthAwarePaginator($pagedData, count($responseData), $perPage, $currentPage, [
            'path' => $path,
            'query' => $request->query(),
        ]);

        $data = [
            'responseData' => $responseData,
        ];

        return view('show', $data);
    }

    public function searchApi(Request $request)
    {
        $search = $request->search;

        $responseData = $this->fetchDataApi();

        // Filter data based on search term
        if (!empty($search)) {
            $responseData = array_filter($responseData, function ($entry) use ($search) {
                // Adjust the condition based on your search requirements
                $categoryMatch = stripos($entry->Category, $search) !== false;
                $apiMatch = stripos($entry->API, $search) !== false;
                $corsMatch = stripos($entry->Cors, $search) !== false;

                // Return true if either category or API matches the search term
                return $categoryMatch || $apiMatch || $corsMatch;
            });
        }

        // Paginate the data
        $perPage = 20;
        $currentPage = $request->input('page', 1);
        $path = url('/admin/api-project'); // Set the correct base path
        $pagedData = array_slice($responseData, ($currentPage - 1) * $perPage, $perPage);
        $responseData = new LengthAwarePaginator($pagedData, count($responseData), $perPage, $currentPage, [
            'path' => $path,
            'query' => $request->query(),
        ]);

        $data = [
            'responseData' => $responseData,
        ];

        return view('show', $data);
    }

    public function getAllApi()
    {
        // Fetch all API data
        $responseData = $this->fetchDataApi();

        return response()->json($responseData);
    }
}
