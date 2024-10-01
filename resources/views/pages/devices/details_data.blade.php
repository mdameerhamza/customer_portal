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