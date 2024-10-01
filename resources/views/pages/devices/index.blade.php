@extends('layouts.full')
@section('content')
<div class="container-fluid">
<div class="row justify-content-center">
<div class="col-12">
   <!-- Header -->
   <div class="header mt-md-5">
      <div class="header-body">
         <div class="row align-items-center">
            <div class="col">
               <!-- Pretitle -->
               <h6 class="header-pretitle">
               {{utrans("headers.summary")}}
               </h6>
               <!-- Title -->
               <h1 class="header-title">
               SD-WAN Devices
               </h1>
            </div>
            <div class="col-auto">
            </div>
         </div>
         <!-- / .row -->
      </div>
   </div>
   @if($devices != '')
   <div class="card mt-4">
      <div class="card-header">
         <h4 class="card-title text-muted mt-3">
            {{utrans("headers.devices")}}
         </h4>
      </div>

      <div class="table-responsive">
         <table class="table card-table">
            <thead>
               <tr>
                  <th>{{utrans("devices.name")}}</th>
                  <th>{{utrans("devices.status")}}</th>
 		            <th>{{utrans("devices.serial")}}</th>
		            <th>{{utrans("devices.uptime1")}}</th> 
                  <th>{{utrans("devices.address")}}</th> 
 
               </tr>
            </thead>
           
            <tbody>
               @if(count($devices) == 0)
                  <TR>
                     <TD colspan="3">{{utrans("devices.noDevices")}}</TD>
                  </TR>
               @endif
               <?php //echo "<pre>"; print_r($devices) ?>
               @if(isset($devices))
                  @foreach($devices as $contract)
                  <tr >       
                              
                              @php (@$ss = $contract->uptime)
                              @php (@$m = floor(($ss % 3600)/60))

                              @php ($h = floor(($ss % 86400)/3600))

                              @php ($d = floor(($ss % 2592000)/86400))

                             
                           
                        <TD><a href="devices/get-device/{{ $contract->id }}">{{ $contract->name }}</a></TD>
                        <TD style="text-transform: uppercase;">@if($contract->status == 'offline') <img src="/assets/offline.png" width="20"> @else <img src="/assets/online.png" width="20"> @endif
                           {{ $contract->status }}</TD>
                        <TD>{{ $contract->sn }}</TD>
                        <TD>@if($contract->status != 'offline') @if(isset($contract->uptime))  @if($d != 0) {{  $d.' Days,'}} @endif 
                           @if($h != 0) {{  $h.' Hours,'}} @endif 
                           @if($m != 0) {{  $m.' Minutes'}} @endif 


                         @endif @endif
                          
                        </TD>
                        <TD>{{ $contract->address }}</TD>
                  </tr>
                  @endforeach
            @endif
            </tbody>
         </table>
      </div>
   </div>
@else
<div class="alert alert-warning" role="alert">
  You currently do not have any devices enabled for your profile. Please contact us for assistance.
</div>
@endif

</div>
@endsection
