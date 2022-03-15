@extends('layouts.admin-app')

@section('title', 'Company Employee Leaves')

@section('content')
      <!-- Start Main View -->
  <div class="card p-2">
    <div class="row">
        <div class="col-md-12">
          
            <div class="card-body">
                <div class="row mb-2">
                    <div class="col-6">
                        <h4 class="mt-0 text-left">Company Employee Leaves</h4>
                    </div>
                   
                </div>
                 <div class="row mb-2">
                    <div class="col-lg-3 col-md-3">
                       <div class="form-group">
                        <select class="form-control" name="company_id" required="" onchange="return window.location.href = '?c='+this.value"> 
                          <option value=""> Select Company</option>
                          @foreach($companies as $cmpy)
                           <option value="{{ $cmpy->id }}" {{
                           $cmpy->id == $company->id ? 'selected=""' : ''
                           }}>{{ $cmpy->name}}
                           </option>
                          @endforeach
                        </select>
                    </div>
                  </div>

                  <div class="col-lg-3 col-md-3 text-right">
                        <button type="button" class="btn btn-danger mt-0"  onclick="yearClick(-1)">Previous Year
                        </button>
                  </div>  <div class="col-lg-3 col-md-3 text-center">
                        <h6> Jan 1, {{ @request()->filled('year') ? @request()->year : date('Y') }} to  Dec 31,{{ @request()->filled('year') ? @request()->year : date('Y') }}</h6>
                  </div>  
                  <div class="col-lg-3 col-md-3 text-left">
                        <button type="button" class="btn btn-danger mt-0"  onclick="yearClick(1)">Next Year
                        </button>
                  </div>
                   
                </div>
                <!-- Categories Table -->
                <div class="table-responsive">
                    <table id="project-types-table" class="table table-hover text-center">
                        <thead>
                        <tr class="text-danger">
                            <th>Acc. No.</th>
                            <th>Employee</th>
                            <th>Date Of Birth</th>
                            <th> Date of Hire</th>
                            <th> Date of Termination </th>
                            @foreach($all_leave_types as $type)
                               <th>{{ $type->name }}</th>
                            @endforeach
                        </tr>
                        </thead>
                        <tbody>
                          @foreach($employees as $k => $employee)
                         <tr>
                           <td> {{ $k + $employees->firstItem() }}</td>
                           <td>{{ @$employee->last_name.' '.$employee->first_name}}</td>
                           <td>{{ $employee->dob}}</td>
                           <td> {{ $employee->pivot->date_of_joining}}</td>
                           <td> {{ $employee->pivot->termination_date}}</td>
                           @foreach($all_leave_types as $key => $type)
                               <td>{{ @$employee->leave_types[$type->id]['left'] }}</td>
                            @endforeach
                         </tr> 
                         @endforeach
                        <!-- Project Types Go Here -->
                        </tbody>
                    </table>
                </div>
                 {!! $employees->render() !!}
            </div>
        </div>
    </div>
</div>

@endsection

@section('pagescript')

<script type="text/javascript">  
$('.date').datetimepicker({
    format: 'Y'
});

function yearClick(i){
     var year = '{{ Request::filled("year")  ? Request::input("year") : date('Y') }}';
     var fullUrl = window.location.href;
     year = parseInt(year) + parseInt(i);

     let isYear = '{{ Request::input("year")}}';

     if(!isYear){
        window.location.href = fullUrl+(fullUrl.includes('?')?'&':'?')+'year='+year;
     }
     else if(isYear != year){
       window.location.href = fullUrl.replace(isYear, year)
     }
}
</script>

@endsection