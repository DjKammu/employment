@extends('layouts.admin-app')

@section('title', 'Add Leave')

@section('content')

@include('includes.back')

      <!-- Start Main View -->
  <div class="card p-2">
    <div class="row">
        <div class="col-md-12">
              <!-- Start Main View -->
            @if(session()->has('message'))
                <div class="alert alert-success alert-dismissible fade show">
                  <strong>Success!</strong> {{ session()->get('message') }}
                  <button type="button" class="close" data-dismiss="alert">&times;</button>
              </div>
            @endif

             @if ($errors->any())
               <div class="alert alert-warning alert-dismissible fade show">
                 <button type="button" class="close" data-dismiss="alert">&times;</button>
                  <strong>Error!</strong>  
                   {{implode(',',$errors->all() )}}
                </div>
             @endif

            <div class="card-body">
              <div class="row mb-2">
                    <div class="col-6">
                        <h4 class="mt-0 text-left">Add Leave</h4>
                    </div>
                </div>

               <div class="row">
                        <div class="col-md-12">
                            <div class="card-body">
                                <form   method="post" action="{{ route('leaves.store') }}"
                                enctype="multipart/form-data">
                                  @csrf

                                    <!-- Current Password -->
                                    <div class="row">
                                      
                                 

                                     <div class="col-lg-6 col-md-6">
                                            <div class="form-group">
                                                <label class="text-dark" for="password">Company
                                                </label>
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

                                        <div class="col-lg-6 col-md-6">
                                            <div class="form-group">
                                                <label class="text-dark" for="password">Employees
                                                </label>
                                                <select class="form-control" name="employee_id" required=""> 
                                                  <option value=""> Select Employee</option>
                                                  @foreach($employees as $employee)
                                                   <option value="{{ $employee->id }}" >{{ $employee->last_name.' '.$employee->first_name}} {{ ($employee->dob) ? '('.$employee->dob.')' : ''}}
                                                   </option>
                                                  @endforeach
                                                </select>
                                            </div>
                                        </div>
                                    </div>    
                                    
                                     <div class="row">

                                        <div class="col-lg-6 col-md-6">
                                            <div class="form-group">
                                                <label class="text-dark" for="password">Leave Type
                                                </label>
                                                <select class="form-control" name="leave_type_id" > 
                                                  <option value="">Select Leave Type</option>
                                                  @foreach($leaveTypes as $type)
                                                   <option value="{{ $type->id }}" >{{ $type->name}}
                                                   </option>
                                                  @endforeach
                                                </select>
                                            </div>
                                        </div>

                                        <div class="col-lg-6 col-md-6">
                                            <div class="form-group">
                                                <label class="text-dark" for="password">Leave Start Date 
                                                </label>
                                                <input  name="start_date"  type="text" class="form-control date" placeholder="Start Date" required="">
                                            </div>
                                        </div>

                                        <div class="col-lg-6 col-md-6">
                                            <div class="form-group">
                                                <label class="text-dark" for="password">Leave End Date 
                                                </label>
                                                <input  name="end_date" type="text" class="form-control date" placeholder="End Date" required="">
                                            </div>
                                        </div>

                                         <div class="col-lg-6 col-md-6">
                                            <div class="form-group">
                                                <label class="text-dark" for="password">Image 
                                                </label>
                                                <input  name="image"  type="file">
                                            </div>
                                        </div>
 
                                    </div> 


                                    <!-- Submit Button -->
                                    <div class="col-12 text-center">
                                        <button id="change-password-button" type="submit" class="btn btn-danger">Create Leave 
                                        </button>
                                    </div>

                                </form>
                            </div>
                        </div>
                    </div>
            </div>
        </div>
    </div>
</div>

@endsection

@section('pagescript')

<script type="text/javascript">  
$('.date').datetimepicker({
    format: 'Y-M-D'
});
</script>

@endsection