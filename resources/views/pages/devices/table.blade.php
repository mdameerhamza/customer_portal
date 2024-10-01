


<table class="table card-table">
                     <thead>
                        <tr>
                           <th id="time_val">Hourly</th>
                           <th>Download</th>
                           <th style="width:140px;"></th>
                           <th>Upload</th>
                           <th style="width:140px;"></th>
                           <th>Total</th> 
                        </tr>
                     </thead>
                    
                     <tbody>
                          @php (@$grandtotal = 0)
                          @php (@$downloadtotal = 0)
                          @php (@$uploadtotal = 0)    
                         @foreach($devices->data->usages as $key => $contract)
                          
                           <tr>    
                              <TD>@php ($datetime = explode("T",$contract->from_date))
                                 @if($devices->data->type == 'hourly')
                                  {{ $datetime[0] }} {{ $datetime[1] }}
                                 @elseif($devices->data->type == 'daily')  
                                 {{ $datetime[0] }}
                                 @elseif($devices->data->type == 'monthly')

                                  @php ($dateonly = explode("-",$datetime[0]))
                                  {{ $dateonly[0] }}-{{ $dateonly[1] }}

                                 @endif
                              </TD>
                              <TD>{{ number_format($contract->down, 2) }} MB</TD>
                              <TD></TD>
                              <TD>{{ number_format($contract->up, 2) }} MB</TD>
                              <TD></TD>
                              <TD>
                                 @php ($totalmb = ($contract->down + $contract->up) )
                                 {{ number_format($totalmb, 2) }} MB
                                 
                              </TD>
                           </tr>
                           @php (@$grandtotal += $totalmb)
                           @php (@$downloadtotal += $contract->down)
                           @php (@$uploadtotal += $contract->up)   
                         @endforeach

                     </tbody>
                     <tfoot>
                        <tr>
                          <td style="text-align: right;"><h3>Download Total: </h3></td>
                          <td><h3>{{ number_format(@$downloadtotal, 2)  }} MB</h3></td>
                          <td style="text-align: right;"><h3>Upload Total: </h3></td>
                          <td><h3>{{ number_format(@$uploadtotal, 2)  }} MB</h3></td>
                          <TD style="text-align: right;"><h3>Grand Total: </h3></TD>
                          <td><h3>{{ number_format(@$grandtotal, 2)  }} MB</h3></td>
                        </tr>
                     </tfoot>
</table>
 <!--  <hr/>
      <div class="container-fluid" style="text-align: right; padding-right: 18% !important"></div>
  <hr/> -->
<?php 

$datevalue = '';
$download = '';
$upload = '';
$total = '';
foreach($devices->data->usages as $key => $contract): 
            $datetime = explode("T",$contract->from_date);
            $yyyy = explode("-",$datetime[0]);
            $time = explode(":",$datetime[1]);
            if($devices->data->type == 'hourly'){
               $datevalue .= '`'.$time[0].':'.$time[1].'`,';
            }elseif($devices->data->type == 'monthly'){
               $monthName = date("F", mktime(0, 0, 0, $yyyy[1], 10));
               $datevalue .= '`'.$monthName.' '.$yyyy[0].'`,';
            }elseif($devices->data->type == 'daily')  {
               $monthNum = 5;
               $monthName = date("F", mktime(0, 0, 0, $yyyy[1], 10));
               $datevalue .= '`'.$yyyy[2].' '.$monthName.'`,';
            }
        $download .= '`'.round($contract->down, 2).'`,';

        $upload .= '`'.round($contract->up, 2).'`,';    
        $total .= '`'.round($contract->down + $contract->up, 2).'`,';  
?>

        
<?php endforeach; ?>


<script type="text/javascript">

var nameofcolum = "";
if($('input[name="filter_radio"]:checked').val() == 'hourly'){ 
   nameofcolum = "Hourly";
}
if($('input[name="filter_radio"]:checked').val() == 'daily'){
   nameofcolum = "Daily";
}
if($('input[name="filter_radio"]:checked').val() == 'monthly'){
   nameofcolum = "Monthly";
}
if(chart != null) {

    chart.destroy(); 
}
   
var ctx = document.getElementById('myChart').getContext('2d');
var chart = new Chart(ctx, {
        type: 'line',
        scaleFontColor: 'black',

        data: {
            scaleFontColor: 'red',
            labels: [<?php echo $datevalue  ?>],
            datasets: [{
               label: 'Download',
               color:'black',
               radius: 5,
               fill: false,
               borderColor: '#5c5cad',
               backgroundColor: '#5c5cad',
               data: [<?php echo $download  ?>],
              
           }, {
               label: 'Upload',
               pointStyle:'rectRot',
               radius: 5,
               borderColor: 'green',
               backgroundColor: 'green',
               fill: false,
               data: [<?php echo $upload  ?>],
               
            }, {
               borderDash: [5, 5],
               pointStyle:'rect',
               radius: 8,
               label: 'Total',
               borderColor: 'black',
               backgroundColor: 'black',
               fill: false,
               data: [<?php echo $total  ?>],
               
            }],
       },

       options: {
               responsive: true,
               title: {
                  display: true,
                  text: nameofcolum + " Usage",
                  fontSize:24,
                  fontColor: 'black',
                  fontStyle: 'bold',
               },
               tooltips: {
                  mode: 'index',
               },
               legend: {
                  position: 'bottom',
                  fontColor: 'black',
               },
            }

});

</script>
