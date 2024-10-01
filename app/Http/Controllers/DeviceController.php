<?php

namespace App\Http\Controllers;
use App\Http\Requests\ProfileUpdateRequest;
use DB;
use Illuminate\Http\Request;
use App\Devices;
use Illuminate\Database\QueryException;

use App\Http\Requests;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;
use SonarSoftware\CustomerPortalFramework\Controllers\ContactController;

class DeviceController extends Controller
{
    private $apiController;
    public function __construct()
    {
        //$this->apiController = new \SonarSoftware\CustomerPortalFramework\Controllers\ContractController();
    }

    public function index()
    {
       
        $user = get_user();
        $contact = $this->getContact();
        
        $data_devices = new Devices();

        $devices  = $data_devices->curl_request_fun($contact);
       
        return view("pages.devices.index", compact('devices'));
    }

    public function get_view_device_data($device_id)
    {
        $user = get_user();
        $contact = $this->getContact();

        $data_devices = new Devices();
        $devices  = $data_devices->curl_request_details($contact,$device_id);
        return view("pages.devices.details", compact('devices'));
    }

    public function get_device_data()
    {
        $device_id = $_POST['devices'];

        $user = get_user();
        $contact = $this->getContact();

        
        $data_devices = new Devices();
        $devices  = $data_devices->curl_request_details($contact,$device_id);

        $view = view("pages.devices.details_data", compact('devices'));
        echo $view;
        die;
    }

    public function report_view($device_id,$report_id){
        $user = get_user();
        $contact = $this->getContact();
        
        $data_devices = new Devices();
        $devices  = $data_devices->curl_request_fun($contact);
        return view("pages.devices.report", compact('devices'));
    }

    public function get_interfaces(){
        
        $device_id = $_POST['device_id'];
        
        $user = get_user();
        $contact = $this->getContact();

        $data_devices = new Devices();
        $devices  = $data_devices->curl_request_details($contact,$device_id);
        $view = view("pages.devices.interface_select", compact('devices'));
        echo $view;
        die;
    }


    public function get_report_data()
    {

        $user = get_user();
        $contact = $this->getContact();

        $data_devices = new Devices();
        
        $devices  = $data_devices->curl_report_details($contact);

        $view = view("pages.devices.table",['devices' => $devices]);
        echo $view;
        die;
    }

    public function get_csv_data()
    {   
        $device_id = $_GET['device_id'];
        $filter_radio = $_GET['filter_radio'];
        $interface_selected = $_GET['interface_selected'];
        $stard_date = $_GET['stard_date'];
        $end_date = $_GET['end_date'];

        $device_name = $_GET['device_name'];
        $interfacename = $_GET['interface_name'];

        $user = get_user();
        $contact = $this->getContact();
       
        
        $data_devices = new Devices();
        $devices  = $data_devices->curl_csv_details($device_id,$filter_radio,$interface_selected,$stard_date,$end_date,$contact,$device_name,$interfacename);
        die;
    }

     private function getContact()
    {
        if (!Cache::tags("profile.details")->has(get_user()->contact_id)) {
            $contactController = new ContactController();
            $contact = $contactController->getContact(get_user()->contact_id, get_user()->account_id);
            Cache::tags("profile.details")->put(get_user()->contact_id, $contact, 10);
        }
        return Cache::tags("profile.details")->get(get_user()->contact_id);
    }



  
}
