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
                {{utrans("headers.dataUsage")}}
               </h1>
            </div>
            @if($policyDetails->allow_user_to_purchase_capacity === true)
            <div class="col-auto">
               <a href="{{action("DataUsageController@showTopOff")}}" class="btn btn-primary">
               {{utrans("data_usage.purchaseAdditionalData")}} <i class="fe fe-zap"></i>
               </a>
            </div>
            @endif
         </div>
         <!-- / .row -->
      </div>
   </div>
</div>
@if($policyDetails->has_policy === true)
<div class="col-12 col-sm-12 col-md-12">
   <!-- Card -->
   <div class="card">
      <div class="card-header">
         <h4 class="card-header-title">
            {{utrans("headers.currentUsage")}}
         </h4>
      </div>
      <div class="card-body">
         <div class="row align-items-center">
            <div class="col">
               <div class="row align-items-center no-gutters">
                  <div class="col-auto">
                     <!-- Heading -->
                     <span class="h2 mr-4 mb-0">
                     {{ $currentUsage["billable"] }}GB
                     </span>
                  </div>
                  <div class="col">
                     <!-- Progress -->
                     <div class="progress progress-sm">
                        <div class="progress-bar" role="progressbar" style="width: {{$usagePercentage}}%" aria-valuenow="{{$usagePercentage}}" aria-valuemin="0" aria-valuemax="100"></div>
                     </div>
                  </div>
               </div>
               <!-- / .row -->
            </div>
            <div class="col-auto">
               <!-- Heading -->
               <span class="h2 ml-1 mr-2 mb-0">
               {{ $calculatedCap }}GB
               </span>
            </div>
            <div class="col-auto">
               <!-- Icon -->
               <span class="h2 fe fe-activity text-muted mb-0"></span>
            </div>
         </div>
         <!-- / .row -->
      </div>
   </div>
</div>
@endif                     
<div class="col-12 col-sm-12 col-md-12">
   <!-- Card -->
   <div class="card">
      <div class="card-header">
         <h4 class="text-muted mt-3" style="color:black !important; line-height: 1.6;word-spacing: 5px;">
           The above data usage reflects your actual cellular data usage for your current billing cycle, and is reported from the SIM card directly. Usage shown under the "Device Reports" section shows usage as reported from the router directly and should always be very close to the same. For billing purposes, the data shown here will always prevail over the Device Reports. However, if you notice a large discrepancy between the two reports, or have any questions about the data usage, please feel free to contact us at any time, either via Live Chat below, calling us at 416-686-1100, or by opening a Support Ticket on the left.
         </h4>
      </div>
      
   </div>
</div>
@endsection
@section('additionalJS')
<script>
   var historicalUsage = {!! $historicalUsage !!};
   var dataUsageLabel = '{{utrans("data_usage.usage")}}';
</script>
<script src="/assets/js/pages/data_usage/index.js" type="text/javascript"></script>
@endsection