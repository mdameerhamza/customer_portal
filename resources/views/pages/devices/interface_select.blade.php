               <option value="" >-- Select --</option>
               @if(isset($devices->interfaces))
	                @foreach($devices->interfaces as $contract)

	                  @if(strpos( $contract->name, '(p)' ) !== false)
	                  	@php (@$name = str_replace("(p)","",$contract->name))    
	                    {{ $name }}  
	                        <option value="{{ $contract->id }}"   > {{ $name }} </option>
	                  @endif      
	                    
	                  @endforeach
              @endif 