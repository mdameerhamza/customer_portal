<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Devices extends Model
{
    
    public function acces_token(){

        $ch3 = curl_init();
        curl_setopt($ch3, CURLOPT_URL,"https://api.ic.peplink.com/api/oauth2/token");
        curl_setopt($ch3, CURLOPT_POST, 1);
        curl_setopt($ch3, CURLOPT_POSTFIELDS,
                    "client_id=32aa04e26881f1873a689075bf9166f9&client_secret=75f94610df04c4694ad0cc1146344f2d&grant_type=client_credentials");
        //curl_setopt($ch3, CURLOPT_HEADER, array('content-type: application/json'));
        curl_setopt($ch3, CURLOPT_RETURNTRANSFER, true);
        $serveroutput = curl_exec ($ch3);
       
        $access = json_decode($serveroutput);
        return $access->access_token;
    }

    public function get_organization_id($contact) {

         $ch1 = curl_init();
         curl_setopt($ch1, CURLOPT_URL, "https://infusion.sonar.software/api/v1/entity_custom_fields/account/".$contact->getAccountID()."/1");
         curl_setopt($ch1, CURLOPT_SSL_VERIFYPEER, FALSE);
         curl_setopt($ch1, CURLOPT_RETURNTRANSFER, 1);
         curl_setopt($ch1, CURLOPT_USERPWD,"portaluser:K#swick3!");

         $output = curl_exec($ch1);
         curl_close($ch1);
         $response['withcode'] = json_decode($output);
         
         if(isset($response['withcode']->data->data)){
             return $response['withcode']->data->data;
         }else{
            return '';
         }
    }

    public function get_group_id($contact){

        $chid = curl_init();
        curl_setopt($chid, CURLOPT_URL, "https://infusion.sonar.software/api/v1/entity_custom_fields/account/".$contact->getAccountID()."/2");
        curl_setopt($chid, CURLOPT_SSL_VERIFYPEER, FALSE);
        curl_setopt($chid, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($chid, CURLOPT_USERPWD,"portaluser:K#swick3!");

        $outputid = curl_exec($chid);
        curl_close($chid);
        $response_id['withcode'] = json_decode($outputid);
      

         if(isset($response_id['withcode']->data->data)){
             return $response_id['withcode']->data->data;
         }else{
            return '';
         }
    }


    public function curl_request_fun($contact){
    	

        $headers[] = 'Authorization: Bearer ' . $this->acces_token();
        $ch9 = curl_init();
        curl_setopt($ch9, CURLOPT_URL, 'https://api.ic.peplink.com/rest/o/'.$this->get_organization_id($contact).'/g/'.$this->get_group_id($contact).'/d/basic');
        curl_setopt($ch9, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($ch9, CURLOPT_HEADER, 0);
        curl_setopt($ch9, CURLOPT_CUSTOMREQUEST, "GET"); 
        curl_setopt($ch9, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch9, CURLOPT_TIMEOUT, 60);
        $authToken = curl_exec($ch9);
        $datafull = json_decode($authToken);
         
        if(isset($datafull->data)){
            return $datafull->data;
        }else{
            return '';
        }
        
        //  // echo "<pre>";
        //  // print_r($datafull);
        //  // echo "</pre>";
        //  // die;
    }


    public function curl_request_details($contact,$device_id){
       

        $headers_auth[] = 'Authorization: Bearer ' . $this->acces_token();
        $ch10 = curl_init();
        curl_setopt($ch10, CURLOPT_URL, 'https://api.ic.peplink.com/rest/o/'.$this->get_organization_id($contact).'/g/'.$this->get_group_id($contact).'/d/'.$device_id);
        curl_setopt($ch10, CURLOPT_HTTPHEADER, $headers_auth);
        curl_setopt($ch10, CURLOPT_HEADER, 0);
        curl_setopt($ch10, CURLOPT_CUSTOMREQUEST, "GET"); 
        curl_setopt($ch10, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch10, CURLOPT_TIMEOUT, 60);
        $authToken12 = curl_exec($ch10);
        $datafull12 = json_decode($authToken12);


        
        return $datafull12->data;
    }


    public function curl_report_details($contact){
        
        
        $headers[] = 'Authorization: Bearer ' . $this->acces_token();
      
        $ch9 = curl_init();
        curl_setopt($ch9, CURLOPT_URL, 'https://api.ic.peplink.com/rest/o/'.$this->get_organization_id($contact).'/g/'.$this->get_group_id($contact).'/d/'.$_POST['device_id'].'/bandwidth?type='.$_POST['filter_radio'].'&start='.$_POST['stard_date'].'&end='.$_POST['end_date'].'&wan_id='.$_POST['interface_selected'].'&include_details=true');
        curl_setopt($ch9, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($ch9, CURLOPT_HEADER, 0);
        curl_setopt($ch9, CURLOPT_CUSTOMREQUEST, "GET"); 
        curl_setopt($ch9, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch9, CURLOPT_TIMEOUT, 60);
        $authToken = curl_exec($ch9);
        $datafull = json_decode($authToken);

        return $datafull;
         // echo "<pre>";
         // print_r($datafull->data->usages);
         // echo "</pre>";
         // die;
        
    }   

    public function curl_csv_details($device_id,$filter_radio,$interface_selected,$stard_date,$end_date,$contact,$device_name,$interfacename){
        

       
        $headers[] = 'Authorization: Bearer ' . $this->acces_token();
       
        $chcsv = curl_init();
        curl_setopt($chcsv, CURLOPT_URL, 'https://api.ic.peplink.com/rest/o/'.$this->get_organization_id($contact).'/g/'.$this->get_group_id($contact).'/d/'.$device_id.'/bandwidth/csv?type='.$filter_radio.'&start='.$stard_date.'&end='.$end_date.'&wan_id='.$interface_selected);

        curl_setopt($chcsv, CURLOPT_HTTPHEADER, $headers);
       // curl_setopt($chcsv, CURLOPT_HEADER, array('Content-Type: text/csv; charset=utf-8'));
        
        curl_setopt($chcsv, CURLOPT_CUSTOMREQUEST, "GET"); 
        curl_setopt($chcsv, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($chcsv, CURLOPT_TIMEOUT, 60);

        $info = curl_getinfo($chcsv);
       
        $authToken1 = curl_exec($chcsv);
       // echo $authToken1->monthly_usage_2019-12-01_2020-01-31.csv; 
        
        $interfacename = str_replace(" ","",$interfacename);
        $device_name= str_replace(" ","",$device_name);
        $filename = $device_name."-".$interfacename."-".ucfirst($filter_radio)."-".date('m-d-Y-h-i-s', time());
        header('Content-Type: text/csv; charset=utf-8');
        header("Content-disposition: attachment; filename=".$filename.".csv");
        echo $authToken1;
        die;
    }   


}
