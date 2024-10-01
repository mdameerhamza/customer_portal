@extends('layouts.full')
@section('content')

<script type="text/javascript">

 function set_enddate_val(){

   if($('input[name="filter_radio"]:checked').val() == 'hourly'){
      $("#hideenddate").slideUp();
      $("#end_date").attr("disabled", true);
      $("#end_date").val('');
      $("#stard_date").val('<?php echo date("Y-m-d"); ?>');
   }else{
       $("#hideenddate").slideDown();
       $("#end_date").attr("disabled", false);
       $("#stard_date").val('<?php echo date("Y-m-d"); ?>');
       $("#end_date").val('<?php echo date("Y-m-d", time() + 86400); ?>');
   }

 }  
 
   function load_report_table(){

      var device_id = $("#device_id1").val();
      var stard_date = $("#stard_date").val();

      if($('input[name="filter_radio"]:checked').val() == 'hourly'){
         var end_date = $("#stard_date").val();
      }else{
         var end_date = $("#end_date").val();
      }
      var interface_selected = $("#interface_select").val();
      if(interface_selected != ""){
               $("#selectconnvalidation").html("&nbsp;");
               $("#searchbtn").html('<img src="/assets/spinner.gif" width="20"> Search');
               $("#searchbtn").attr("disabled", true);
               var CSRF_TOKEN = $('meta[name="csrf-token"]').attr('content');
               var filter_radio = $('input[name="filter_radio"]:checked').val();
               var nameofcolum = "";
               $.ajaxSetup({
                        headers: {
                            'X-CSRF-TOKEN': $('meta[name="_token"]').attr('content')
                        }
                    });

               $.ajax({
                        url: "/portal/devices/get-report",
                        dataType: "html",
                        method: 'post',
                        data: {
                           filter_radio: filter_radio,
                           interface_selected: interface_selected,
                           device_id:device_id,
                           stard_date:stard_date,
                           end_date:end_date,
                        },
                        success: function(result){

                           if(filter_radio == 'hourly'){ 
                              nameofcolum = "Hour";
                              $("#reportheading").html('Hourly');
                           }
                           if(filter_radio == 'daily'){
                              nameofcolum = "Day";
                              $("#reportheading").html('Daily');
                           }
                           if(filter_radio == 'monthly'){
                              nameofcolum = "Month";
                              $("#reportheading").html('Monthly');
                           }
                           
                           $("#table_load").html(result);
                           $("#time_val").html(nameofcolum);
                           $("#searchbtn").html('Search');
                           $("#searchbtn").attr("disabled", false);
                           

                        }
               });


         }else{
            $("#selectconnvalidation").html("Choose an option");
         }

   }

   function load_csv_file(){

       var device_id = $("#device_id1").val();
       var filter_radio = $('input[name="filter_radio"]:checked').val();
       var interface_selected = $("#interface_select").val();
       var stard_date = $("#stard_date").val();
       var end_date = $("#end_date").val();

       var devicename = $( "#load_interfaces_select option:selected" ).text();
       var interfacename = $( "#interface_select option:selected" ).text();
       
       window.location.href = "/portal/devices/get_csv_data?device_id="+ device_id +"&filter_radio="+ filter_radio +"&interface_selected="+ interface_selected +"&stard_date="+ stard_date +"&end_date="+ end_date +"&device_name="+ devicename +"&interface_name="+ interfacename;
   }

   function load_interface(value){

         if(value != ""){
            $("#selectconnvalidation").html("&nbsp;");
            $("#device_id1").val(value);
            $("#interface_select").attr("disabled", true);
            $("#searchbtn").attr("disabled", true);

            $("#downloadlink").hide();

            $("#searchiconshow_select").html('<img src="/assets/spinner.gif" width="40">');
            $.ajaxSetup({
                     headers: {
                         'X-CSRF-TOKEN': $('meta[name="_token"]').attr('content')
                     }
                 });

            $.ajax({
                     url: "/portal/devices/get_interfaces",
                     dataType: "html",
                     method: 'post',
                     data: {
                        device_id: value
                     },
                     success: function(result){
                         $("#downloadlink").show();

                         $("#interface_select").html(result);
                         $("#searchiconshow_select").html('');
                         $("#interface_select").attr("disabled", false);
                         $("#searchbtn").attr("disabled", false);
                         $('#interface_select option[value="<?php echo request()->segment(count(request()->segments())) ?>"]').prop('selected', true);

                          <?php if(Request::segment(4) != 0){ ?>
                           load_report_table();
                          <?php } ?>
                           
                     }
            });
             
         }


   }

  <?php if(Request::segment(4) != 0){ ?>
   setTimeout(function(){ load_interface(<?php echo Request::segment(4) ?>); }, 2000);
  <?php } ?>



 setTimeout(function(){ set_enddate_val();   }, 2000);




</script>



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
               Reports
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
            Usage Reports
         </h4>
      </div>


       <div class="container-fluid">
            <br/>
            
            <input type="hidden" value="" name="device_id" id="device_id1">
            <div class="form-check form-check-inline">
               <label>Data Usage:</label>
            </div>
            <div class="form-check form-check-inline">

               <input class="form-check-input" type="radio"  name="filter_radio" value="hourly" onclick="set_enddate_val()" checked>
               <label class="form-check-label">Hourly</label>
            </div>

            <div class="form-check form-check-inline">
               <input class="form-check-input" type="radio"  name="filter_radio" onclick="set_enddate_val()" value="daily">
               <label class="form-check-label">Daily</label>
            </div>

            <div class="form-check form-check-inline">
               <input class="form-check-input" type="radio"  name="filter_radio" onclick="set_enddate_val()" value="monthly">
               <label class="form-check-label">Monthly</label>
            </div>
            <br/><br/>
      </div>

      <div class="container-fluid">
         <h2><div style="display: inline;" id="reportheading">Hourly</div> Report</h2>
         <hr/>
      </div>

      <div class="container-fluid">
         
            <div class="form-group row">
             <label for="staticEmail" class="col-sm-1 col-form-label">Devices:</label>
             <div class="col-sm-3">
               <select class="form-control" id="load_interfaces_select" onchange="load_interface(this.value)">
                   <option value="">-- Select -- </option>
               @if(isset($devices))
                  @foreach($devices as $contract)
                        <option value="{{ $contract->id }}"  @if($contract->id ==  Request::segment(4)) selected @endif> {{ $contract->name }} </option>
                  @endforeach
               @endif
               </select>
             </div>
              <label for="staticEmail" class="col-sm-1 col-form-label">Interface:</label>
             <div class="col-sm-3">

               <select class="form-control" id="interface_select" >
                  <option value="">-- Select -- </option>
               </select>
               <div id="selectconnvalidation" style="color:red; font-size:13px">&nbsp;</div>
             </div>
              <div class="col-sm-1">
                 <div id="searchiconshow_select" style="display: inline"></div>
              </div>
           </div>
            
           <div class="form-group row">
          
             <label for="staticEmail" class="col-sm-1 col-form-label">Start Date:</label>
             <div class="col-sm-3">

              <input type="text" name="startDate"  placeholder="YYYY-MM-DD" class="form-control" value="<?php echo date("Y-m-d") ?>" id="stard_date">
             </div>
             
           </div>
              
                    <div class="form-group row" id="hideenddate">
                           
                               <label for="staticEmail" class="col-sm-1 col-form-label">End Date:</label>
                               <div class="col-sm-3">
                                 <input type="text" placeholder="YYYY-MM-DD" name="endDate" class="form-control" id="end_date">
                                 <div id="datevalidation" style="color:red; font-size:13px">&nbsp;</div>
                               </div>
                          
                    </div>
           

           <div class="form-group row">
             <div class="col-sm-3">
               <button type="button" id="searchbtn" onclick="load_report_table()" class="btn btn-secondary">Search</button>
              
             </div>
           </div>
        
         
      </div>
       <div class="container-fluid">
        
         <br/><br/>
          
           <canvas id="myChart" width="400" height="100"></canvas>
           
         <br/><br/>
               
       </div>

         <div class="container-fluid">
               <div class="table-responsive" id="table_load">
                  <table class="table card-table">
                     <thead>
                        <tr>
                           <th>Hour</th>
                           <th>Download</th>
                           <th>Upload</th>
                           <th>Total</th> 
                        </tr>
                     </thead>
                    
                     <tbody>
                        <tr>    
                           <TD></TD>
                           <TD></TD>
                           <TD></TD>
                           <TD></TD>
                        </tr>
                     </tbody>
                  </table>
               </div>
         </div>

      <div class="container-fluid">
            <br/>
          <div class="form-group row">
             <div class="col-sm-3">
              <a href="javascript:void(0)" id="downloadlink" onclick="load_csv_file()">Download CSV</a>
             </div>
           </div>
      </div>
   </div>
@else
<div class="alert alert-warning" role="alert">
  At least one device is required on your account to view reports. Please contact us for assistance.
</div>
@endif







</div>


@endsection
