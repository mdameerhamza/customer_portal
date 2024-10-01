<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Devices_details extends Model
{
    
    public function curl_request_details($contact,$device_id){
    	
    	$ch3 = curl_init();
        curl_setopt($ch3, CURLOPT_URL,"https://api.ic.peplink.com/api/oauth2/token");
        curl_setopt($ch3, CURLOPT_POST, 1);
        curl_setopt($ch3, CURLOPT_POSTFIELDS,
                    "client_id=32aa04e26881f1873a689075bf9166f9&client_secret=75f94610df04c4694ad0cc1146344f2d&grant_type=client_credentials");
        //curl_setopt($ch3, CURLOPT_HEADER, array('content-type: application/json'));
        curl_setopt($ch3, CURLOPT_RETURNTRANSFER, true);
        $serveroutput = curl_exec ($ch3);
        $access = json_decode($serveroutput);

       
        //////////////////////////////////////////
        $ch1 = curl_init();
        curl_setopt($ch1, CURLOPT_URL, "https://infusion.sonar.software/api/v1/entity_custom_fields/account/".$contact->getAccountID()."/1");
        curl_setopt($ch1, CURLOPT_SSL_VERIFYPEER, FALSE);
        curl_setopt($ch1, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch1, CURLOPT_USERPWD,"portaluser:K#swick3!");

        $output = curl_exec($ch1);
        curl_close($ch1);
        $response['withcode'] = json_decode($output);

        //////////////////////////////////////////
        $chid = curl_init();
        curl_setopt($chid, CURLOPT_URL, "https://infusion.sonar.software/api/v1/entity_custom_fields/account/".$contact->getAccountID()."/2");
        curl_setopt($chid, CURLOPT_SSL_VERIFYPEER, FALSE);
        curl_setopt($chid, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($chid, CURLOPT_USERPWD,"portaluser:K#swick3!");

        $outputid = curl_exec($chid);
        curl_close($chid);
        $response_id['withcode'] = json_decode($outputid);

       ///////////////////////////////////////////

        $headers_auth[] = 'Authorization: Bearer ' . $access->access_token;
        $ch10 = curl_init();
        curl_setopt($ch10, CURLOPT_URL, 'https://api.ic.peplink.com/rest/o/'.$response['withcode']->data->data.'/g/'.$response_id['withcode']->data->data.'/d/'.$device_id);
        curl_setopt($ch10, CURLOPT_HTTPHEADER, $headers_auth);
        curl_setopt($ch10, CURLOPT_HEADER, 0);
        curl_setopt($ch10, CURLOPT_CUSTOMREQUEST, "GET"); 
        curl_setopt($ch10, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch10, CURLOPT_TIMEOUT, 30);
        $authToken12 = curl_exec($ch10);
        $datafull12 = json_decode($authToken12);


         // echo "<pre>";
         // print_r($datafull12->data);
         // echo "</pre>";
         // die;
        return $datafull12->data;
    }

}
