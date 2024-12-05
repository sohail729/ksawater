<?php

namespace App\Helpers;

use Symfony\Component\HttpFoundation\Response;
use GuzzleHttp\Client;
use Illuminate\Support\Facades\Log;

class NetworkHelper
{

    public static function makeRequest($method, $url, $endpoint, $headerData = "", $data = "", $queryParams = "", $formData = "", $params = [])
    {
        try {
            $header = array();
            if(empty($headerData['Content-Type'])){
                $header = ['Content-Type' => 'application/json'];
                if ($data != "") {
                    $params['body'] = json_encode($data);
                }
            }
            else{
                if ($data != "") {
                    $params['body'] = $data;
                }
            }
            if ($headerData != "") {
                $header = array_merge($header, $headerData);
            }

            if ($queryParams != "") {
                $params['query'] = $queryParams;
            }
            if ($formData != "") {
                $params['form_params'] = $formData;
            }
            $params['headers'] = $header;
            $params['timeout'] = $params['timeout'] ?? 20;
            $params['connect_timeout'] = $params['connect_timeout'] ?? 10;
            $client = new Client(['verify' => false]);
            $response = $client->$method($url . $endpoint, $params);
            $responsedata['status'] = $response->getStatusCode();
            $responsedata['data'] = $response->getBody()->getContents();
            Log::debug('External Request'.$url . $endpoint.' HTTP: '.$response->getStatusCode().': '.$response->getBody()->getContents());
            Log::debug('External Request Data: ' . json_encode($params));
            if($endpoint == 'Login/AuthenticateUser/')
                $responsedata['Token'] = $response->getHeader('Token')[0];
        } catch (\GuzzleHttp\Exception\ConnectException $e) {
            Log::debug('External Request'.$url . $endpoint.' HTTP: '.Response::HTTP_REQUEST_TIMEOUT.': '.$e->getMessage());
            $responsedata['status'] = Response::HTTP_REQUEST_TIMEOUT;
            $responsedata['data'] = $e->getMessage();
        } catch (\InvalidArgumentException $e) {
            $responsedata['status'] = Response::HTTP_BAD_REQUEST;
            $responsedata['data'] =  $e->getMessage();
        } catch (\GuzzleHttp\Exception\BadResponseException $e) {
            $status = $e->getResponse()->getStatusCode();
            $data = $e->getResponse()->getBody()->getContents();
            Log::debug('External Request'.$url . $endpoint.' HTTP: '.$status.': '.$data);
            $responsedata['status'] = $status;
            $responsedata['data'] = $data;
        } catch (\Exception $e) {
            Log::debug('External Request'.$url . $endpoint.' HTTP: '.Response::HTTP_BAD_REQUEST.': '.$e->getMessage());
            $responsedata['status'] = Response::HTTP_BAD_REQUEST;
            $responsedata['data'] = $e->getMessage();
        }

        return $responsedata;
    }
}
