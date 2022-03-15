@extends('layouts.admin-app')

@section('title', 'Leave Rule')

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
                        <h4 class="mt-0 text-left">Add Leave Rule</h4>
                    </div>
                </div>

               <div class="row">
                        <div class="col-md-12">
                            <div class="card-body">
                                <form   method="post" 
                              action="{{ route('leave-rules.store') }}">
                                  @csrf

                                    <!-- Current Password -->
                                    <div class="row">
                                        <div class="col-lg-6 col-md-6">
                                            <div class="form-group">
                                                <label class="text-dark" for="password">Name 
                                                </label>
                                                <input  name="name" value="{{ old('name')}}" type="text" class="form-control" placeholder="Leave Name" required="">
                                            </div>
                                        </div>
                                 

                                     <div class="col-lg-6 col-md-6">
                                            <div class="form-group">
                                                <label class="text-dark" for="password">Company
                                                </label>
                                                <select class="form-control" name="company_id" required=""> 
                                                  <option value=""> Select Company</option>
                                                  @foreach($companies as $company)
                                                   <option value="{{ $company->id }}" >{{ $company->name}}
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
                                                <select class="form-control" name="leave_type_id" required=""> 
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
                                                <label class="text-dark" for="password">Accrues Every Quarter 
                                                </label>
                                                <select class="form-control" name="accrues_every_quarter"> 
                                                  <option value=""> Select Accrues Every Quarter </option>
                                                  <option value="{{\App\Models\LeaveRule::NO}}" >{{\App\Models\LeaveRule::NO_TEXT}}</option>
                                                  @for ($i=1; $i <= \App\Models\LeaveRule::RULE_PERIOD ; $i++)
                                                   <option value="{{ $i }}" >{{ $i}}
                                                   </option>
                                                  @endfor
                                                </select>
                                            </div>
                                        </div>
                                    
                                        
                                    </div> 

                                    <div class="row">

                                        <div class="col-lg-6 col-md-6">
                                            <div class="form-group">
                                                <label class="text-dark" for="password">Acrual Days
                                                </label>
                                                <select class="form-control" name="accrues_every_year">  <option value="">Select Acrual Days</option>
                                                  <option value="{{\App\Models\LeaveRule::NO}}" >{{\App\Models\LeaveRule::NO_TEXT}}</option>
                                                 @for ($i=1; $i <= \App\Models\LeaveRule::RULE_PERIOD ; $i++)
                                                   <option value="{{ $i }}" >{{ $i}}
                                                   </option>
                                                  @endfor
                                                </select>
                                            </div>
                                        </div>

                                      <div class="col-lg-6 col-md-6">
                                            <div class="form-group">
                                                <label class="text-dark" for="password">Carry Over Year 
                                                </label>
                                                <select class="form-control" name="carry_over_year" required=""> 
                                                   <option value=""> Select Accrues Every Year</option>
                                                  
                                                   <option value="{{\App\Models\LeaveRule::YES}}" >{{\App\Models\LeaveRule::YES_TEXT}}</option>
                                                   <option value="{{\App\Models\LeaveRule::NO}}" >{{\App\Models\LeaveRule::NO_TEXT}}</option>
                                                 
                                                </select>
                                            </div>
                                        </div>
                                    
                                        
                                    </div> 

                                    <div class="row">

                                        <div class="col-lg-6 col-md-6">
                                            <div class="form-group">
                                                <label class="text-dark" for="password">Max Leave Accumulation Period 
                                                </label>
                                                <select class="form-control" name="max_period" required=""> 
                                                   <option value=""> Select Max Leave Accumulation Period</option>
                                                  @for ($i=0; $i <=  \App\Models\LeaveRule::RULE_PERIOD ; $i++)
                                                   <option value="{{ $i }}" >{{ $i}}
                                                   </option>
                                                  @endfor
                                                </select>
                                            </div>
                                        </div>
                                    
                                        

                                        <div class="col-lg-6 col-md-6">
                                            <div class="form-group">
                                                <label class="text-dark" for="password">Leaves Accrual After
                                                </label>
                                                <select class="form-control" name="leaves_accrual_after" required=""> 
                                                   <option value=""> Leaves Accrual After</option>
                                                  @for ($i=0; $i <=  \App\Models\LeaveRule::ACCRUAL_AFTER_PERIOD ; $i++)
                                                   <option value="{{ $i }}" >{{ $i}}  Month(s)
                                                   </option>
                                                  @endfor
                                                </select>
                                            </div>
                                        </div>
                                    
                                        
                                    </div>


                                    <!-- Submit Button -->
                                    <div class="col-12 text-center">
                                        <button id="change-password-button" type="submit" class="btn btn-danger">Create Leave Rule
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