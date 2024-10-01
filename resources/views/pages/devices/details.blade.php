@extends('layouts.full')
@section('content')
<style>
   #signal-strength {
  height: 25px;
  list-style: none;
  overflow: hidden;
}
#signal-strength li {
  display: inline-block;
  width: 5px;
  float: left;
  height: 100%;
  margin-right: 1px;
  border-bottom: 2px solid blue;
  border-spacing: 2px !important;
}
#signal-strength li.pretty-strong {
  padding-top: 0px;
}
#signal-strength li.strong {
  padding-top: 5px;
}
#signal-strength li.weak {
  padding-top: 10px;
}
#signal-strength li.very-weak {
  padding-top: 15px;
}

#signal-strength li.weak-weak {
  padding-top: 18px;
}
#signal-strength li div {
  height: 100%;
  background: blue;
}


</style>



<script type="text/javascript">

     function load_details_data(){
              
               $("#details_data").html('<center><img src="/assets/spinner.gif" width="60"></center>');
               $.ajaxSetup({
                        headers: {
                            'X-CSRF-TOKEN': $('meta[name="_token"]').attr('content')
                        }
                    });

               $.ajax({
                        url: "/portal/devices/get-details-data",
                        dataType: "html",
                        method: 'post',
                        data: {
                           devices: <?php echo $devices->id; ?>
                        },
                        success: function(result){
                           $("#details_data").html(result);
                        }
               });

   }
//setTimeout(function(){ load_details_data();   }, 2000);
</script>

<div class="container-fluid">

            <div class="col-12">
   <!-- Header -->
   <div class="header mt-md-5">
      <div class="header-body">
         <div class="row align-items-center">
            <div class="col">
               <!-- Pretitle -->
               <h6 class="header-pretitle">
                  <a href="javascript:history.go(-1)">Back to Devices</a>
               </h6>
               <!-- Title -->
               <h1 class="header-title">
               {{ $devices->name }}
               </h1>
            </div>
            <div class="col-auto">
            </div>
         </div>
         <!-- / .row -->
      </div>
   </div>
   <div class="col-6">
      <?php //echo "<pre>"; print_r($devices) ?>
   <div class="card mt-4">
      <div class="card-header">
         <h4 class="card-title text-muted mt-3">
            Device Details
         </h4>
      </div>
      <?php  ?>
      <div class="table-responsive">
         <table class="table card-table">
            <thead>
               
            </thead>
           
            <tbody>
               <tr>
                  <th scope="row">{{utrans("devices.name")}}</th>
                  <td>@if(isset($devices->name)) {{ $devices->name }}  @endif</td>
               </tr>
                <tr>
                  <th scope="row">{{utrans("devices.status")}}</th>
                  <td style="text-transform: uppercase;"> 

                        @php (@$is_uptime_online = 'false') 

                        @if(isset($devices->status))
                                @if($devices->status == 'offline') @php (@$is_uptime_online = 'true')  <img src="/assets/offline.png" width="20"> @else <img src="/assets/online.png" width="20"> 
                                @endif
                               {{ $devices->status }}
                            @if($devices->status == 'offline')  
                                @php ($datetime = explode("T",$devices->offline_at))
                                (Since {{ $datetime[1] }}, {{ $datetime[0] }})   
                            @endif
                        @endif
                  </td>
               </tr>
                <tr>
                  <th scope="row">{{utrans("devices.serial")}} Number</th>
                  <td>@if(isset($devices->sn)) {{ $devices->sn }}  @endif</td>
               </tr>
                @if(strpos($devices->name, 'FusionHub') !== false) 
                    @else 
                    <tr>
                      <th scope="row">Model</th>
                      <td>@if(isset($devices->model)) {{ $devices->model }}  @endif</td>
                   </tr>
                @endif
               @if(strpos($devices->name, 'FusionHub') !== false) 
                  <tr>
                    <th scope="row">Public IP</th>
                    <td>@if(isset($devices->wtp_ip)) {{ $devices->wtp_ip }} @endif</td>
                  </tr>
               @else 
                  <tr>
                    <th scope="row">Remote Access</th>
                    <td>@if(isset($devices->ddns_name)) {{ $devices->ddns_name }}.mypep.link  @endif</td>
                  </tr>
               @endif
                <tr>
                  <th scope="row">Uptime</th>
                  <td>
                        @if(isset($devices->uptime) && $is_uptime_online = 'true')   
                           @php (@$ss = $devices->uptime)
                           @php (@$m = floor(($ss % 3600)/60))
                           @php ($h = floor(($ss % 86400)/3600))
                           @php ($d = floor(($ss % 2592000)/86400))

                           @if($d != 0) {{  $d.' Days,'}} @endif 
                           @if($h != 0) {{  $h.' Hours,'}} @endif 
                           @if($m != 0) {{  $m.' Minutes'}} @endif 

                        @endif
                  </td>
               </tr>
            </tbody>
         </table>
      </div>
   </div>
  </div>

@if(strpos($devices->name, 'FusionHub') !== false) 

@else 
<div>
<div class="card mt-4"  >
      <div class="card-header">
         <h4 class="card-title text-muted mt-3">
            Interface Status
         </h4>
      </div>
      <div class="table-responsive">
         <table class="table card-table">
            <thead>
               <tr>
                  <th>Name</th>
                  <th>{{utrans("devices.status")}}</th>
                  <th>IP</th>
                  <th>Signal</th> 
                  <th>Signal Strength</th> 
                  <th>Signal Quality</th> 
                  
                  <th>Action</th> 
               </tr>
            </thead>
           
            <tbody id="details_data">
            @if(isset($devices->interfaces)) 
               @foreach($devices->interfaces as $contract)
                @if(strpos( $contract->name, '(p)' ) !== false)
                 @php (@$is_online = 'false')
               <tr>    
                  <TD>@if(isset($contract->name))
                    @php (@$name = str_replace("(p)","",$contract->name))    
                   {{ $name }}  

                  @endif</TD>
                  <TD style="text-transform: uppercase;">
                     <!-- && $contract->status != 'Disabled (Activation Required)' -->
                     @if(isset($contract->status))
                              @if(isset($contract->is_overall_up)) 

                                    @if($contract->is_overall_up == '1')  
                                          @php (@$is_online = 'true')  
                                          <img src="/assets/online.png" width="20"> 
                                    @else 
                                          <img src="/assets/offline.png" width="20"> 
                                    @endif

                              @else 
                                  <img src="/assets/offline.png" width="20"> 
                              @endif
                             {{ $contract->status }}
                     @else      
                      <script type="text/javascript">setTimeout(function(){ load_details_data();   }, 2000); </script>
                     @endif
                  </TD>
                  <TD>@if(isset($contract->ip)  && $is_online == 'true') {{ $contract->ip }}  @endif</TD>
                  <TD>@if(isset($contract->signal_bar) && $is_online == 'true') 

                      <ul id="signal-strength">
                         @if($contract->signal_bar == '0')  
                          <li class="weak-weak"></li>  
                          <li class="very-weak"></li>
                          <li class="weak"></li>
                          <li class="strong"></li>
                          <li class="pretty-strong"></li>
                         @endif 
                       @if($contract->signal_bar == '1')  
                          <li class="weak-weak"><div id="weak-weak"></div></li>  
                          <li class="very-weak"></li>
                          <li class="weak"></li>
                          <li class="strong"></li>
                          <li class="pretty-strong"></li>
                         @endif 
                        @if($contract->signal_bar == '2')  
                            <li class="weak-weak"><div id="weak-weak"></div></li>  
                            <li class="very-weak"><div id="very-weak"></div></li>
                            <li class="weak"></li>
                            <li class="strong"></li>
                            <li class="pretty-strong"></li>
                        @endif 
                        @if($contract->signal_bar == '3')  
                            <li class="weak-weak"><div id="weak-weak"></div></li>  
                            <li class="very-weak"><div id="very-weak"></div></li>
                            <li class="weak"><div id="weak"></div></li>
                            <li class="strong"></li>
                            <li class="pretty-strong"></li>
                        @endif 
                        @if($contract->signal_bar == '4')  
                               <li class="weak-weak"><div id="weak-weak"></div></li>  
                               <li class="very-weak"><div id="very-weak"></div></li>
                               <li class="weak"><div id="weak"></div></li>
                               <li class="strong"><div id="strong"></div></li>
                               <li class="pretty-strong"></li>
                        @endif 
                        @if($contract->signal_bar == '5')  
                               <li class="weak-weak"><div id="weak-weak"></div></li>  
                               <li class="very-weak"><div id="very-weak"></div></li>
                               <li class="weak"><div id="weak"></div></li>
                               <li class="strong"><div id="strong"></div></li>
                               <li class="pretty-strong"><div id="pretty-strong"></div></li>
                        @endif 
                     </ul>
                  @endif
                  </TD>
                  <TD>@if(isset($contract->signal) && $is_online == 'true' ) {{ $contract->signal }} dBm  @endif</TD>
                  <TD>@if(isset($contract->signal_quality) && $is_online == 'true') {{ $contract->signal_quality }} dB @endif</TD>
                  <TD>
                     @if($is_online == 'true')
                        <a href="/portal/devices/report/{{ $devices->id }}/{{ $contract->id }}">View Report</a>
                     @endif
                   </TD>
               </tr>
                @endif
               @endforeach

          @endif
            </tbody>
         </table>
      </div>
   </div>
   </div>
@endif




</div>
</div>
@endsection
