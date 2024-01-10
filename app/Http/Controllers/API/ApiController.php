<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Exception;
use Illuminate\Support\Facades\Log;

class ApiController extends Controller
{
    public function apiTest()
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

        // Dump the API response for debugging
        //dd($responseData);
        $data = [
            'responseData' => $responseData,
        ];
        return view('show', $data);

        // Uncomment the following line to return the view with the data
        // return view('show', compact('responseData'));
    }
}
