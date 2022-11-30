<?php

namespace App\Helper;

use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Contracts\HttpClient\HttpClientInterface;
use Symfony\Component\HttpClient\HttpClient;

class SmsHelper {

 
    private $client;
    private $container;
    public function __construct(HttpClientInterface $httpClientInterface,ContainerInterface $container)
    {
        $this->client=HttpClient::create(['verify_peer' => false, 'verify_host' => false]);
        $this->container=$container;

    }
   
    public function sendSms($title="title",$message="message",$phone_numbers=[])
    {
        $phone_numbers=json_encode($phone_numbers);
        try {
           
     

        $response = $this->client->request(
            'POST',
            sprintf('%s/message-request/create',$this->container->getParameter("msg_api_host")),
            [
                'headers' => [
                    'Accept' => 'application/json',
                    'X-AUTH-TOKEN' => $this->container->getParameter("msg_api_token"),
                ],

              'body' => ['title' => $title, 'message'=>$message,"phone_numbers"=>$phone_numbers],
            ],
        );

        $statusCode = $response->getStatusCode();
       return $statusCode==200;

    } catch (\Throwable $th) {
      return false;
    }


      
    }
}