 <div class="tab-pane" id="codes" role="tabpanel" aria-expanded="true">
                           
    <div class="row mb-2">
        <div class="col-6">
            <h4 class="mt-0 text-left">{{ @$company->name }} - Codes List </h4>
        </div>
        <div class="col-6 text-right">
            <button type="button" class="btn btn-danger mt-0"  onclick="return window.location.href='{{ route('companies.codes.store', [ 'id' => request()->company]) }}'">Add Code
            </button>
        </div>

    </div>

     <div class="row mb-2">
        <div class="col-12">
            <form method="post" action="{{ route('companies.codes.store.multiple', [ 'id' => request()->company]) }}"> 
              @csrf
            <select style="height: 26px;"  name="company_id" required=""> 
              <option value=""> Select Comapany</option>
              @foreach($companies as $company)
               <option value="{{ $company->id }}" >{{ $company->name}}
               </option>
              @endforeach
            </select>
            <button >Assign Codes from other company</button>
          </form>
        </div>
    </div>

    <!-- Categories Table -->
    <div class="table-responsive">

       <table id="project-types-table" class="table table-hover text-center">
                        <thead>
                        <tr class="text-danger">
                            <th>Acc. No.</th>
                            <th>Code</th>
                            <th>Form Title</th>
                            <th>Form Link</th>
                            <th>Edit</th>
                            <th>Delete</th>
                        </tr>
                        </thead>
                        <tbody>
                          @foreach($codes as $k => $code)
                         <tr>
                           <td> {{ $k + 1 }}</td>
                           <td>{{ $code->code }}</td>
                           <td>{{ $code->title }}</td>
                           <td>{{ $code->form_link }}</td>
                          <td>        
                            <button onclick="return window.location.href='/codes/{{$code->id}}'" rel="tooltip" class="btn btn-neutral bg-transparent btn-icon" data-original-title="Edit Company Type" title="Edit Company Type"> <i class="fa fa-edit text-success"></i> </button> 
                          </td>
                          <td>
                             <form 
                              method="post" 
                              action="{{route('codes.destroy',['code' => $code->id])}}"> 
                               @csrf
                              {{ method_field('DELETE') }}
          
                              <button 
                                type="submit"
                                onclick="return confirm('Are you sure?')"
                                class="btn btn-neutral bg-transparent btn-icon" data-original-title="Delete Trade" title="Delete"><i class="fa fa-trash text-danger"></i> </button>
                            </form>
                           </td>
                         </tr> 
                         @endforeach
                        <!-- Project Types Go Here -->
                        </tbody>
                    </table>

    </div>

</div>