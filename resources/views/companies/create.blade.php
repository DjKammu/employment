@extends('layouts.admin-app')

@section('title', 'Companies')

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
                        <h4 class="mt-0 text-left">Add Company</h4>
                    </div>
                </div>

               <div class="row">
                        <div class="col-md-12">
                            <div class="card-body">
                                <form   method="post" 
                              action="{{ route('companies.store') }}" enctype="multipart/form-data">
                                  @csrf

                                    <!-- Current Password -->
                                    <div class="row">
                                        <div class="col-lg-6 col-md-6">
                                            <div class="form-group">
                                                <label class="text-dark" for="password">Name 
                                                </label>
                                                <input  name="name" value="{{ old('name')}}" type="text" class="form-control" placeholder="Name" required="">
                                            </div>
                                        </div> 

                                           <div class="col-lg-6 col-md-6">
                                            <div class="form-group">
                                                <label class="text-dark" for="password">Nick Name 
                                                </label>

                                                <input  name="nick_name" value="{{ old('nick_name')}}" type="text" class="form-control" placeholder="Nick Name">
                                            </div>
                                        </div>
                                        
                                    
                                       <div class="col-lg-6 col-md-6">
                                            <div class="form-group">
                                                <label class="text-dark" for="password">Address 1
                                                </label>
                                                <textarea  name="address_1"  type="text" class="form-control" placeholder="Address 1" >
                                                 {{ old('address_1')}}</textarea>
                                            </div>
                                        </div>

                                        <div class="col-lg-6 col-md-6">
                                            <div class="form-group">
                                                <label class="text-dark" for="password">Address 2
                                                </label>
                                                <textarea  name="address_2"  type="text" class="form-control" placeholder="Address 1" >
                                                 {{ old('address_2')}}</textarea>
                                            </div>
                                        </div>
                                   
                                       <div class="col-lg-6 col-md-6">
                                            <div class="form-group">
                                                <label class="text-dark" for="password">City 
                                                </label>
                                                <input  name="city" value="{{ old('city')}}" type="text" class="form-control" placeholder="City">
                                            </div>
                                        </div>
                                    
                                       <div class="col-lg-6 col-md-6">
                                            <div class="form-group">
                                                <label class="text-dark" for="password">State
                                                </label>
                                                <input  name="state"  value="{{ old('state')}}" type="text" class="form-control" placeholder="State" >
                                            </div>
                                        </div>
                                    
                                       <div class="col-lg-6 col-md-6">
                                            <div class="form-group">
                                                <label class="text-dark" for="password">Country 
                                                </label>
                                                <input  name="country" value="{{ old('country')}}" type="text" class="form-control" placeholder="Country">
                                            </div>
                                        </div>
                                  
                                        <div class="col-lg-6 col-md-6">
                                            <div class="form-group">
                                                <label class="text-dark" for="password">Zip Code 
                                                </label>
                                                <input  name="zip_code"  value="{{ old('zip_code')}}" type="text" class="form-control" placeholder="Zip Code" >
                                            </div>
                                        </div>
                                  
                                        <div class="col-lg-6 col-md-6">
                                            <div class="form-group">
                                                <label class="text-dark" for="password">Photo 
                                                </label>
                                                <input  name="photo"  type="file">
                                            </div>
                                        </div>

                                       <div class="col-lg-6 col-md-6">
                                            <div class="form-group">
                                                <label class="text-dark" for="password">Notes 
                                                </label>
                                                <textarea  name="notes"  type="text" class="form-control" placeholder="Notes" >
                                                 {{ old('notes')}}</textarea>
                                            </div>
                                        </div>
                                    
                                       
                                    </div>


                                    <!-- Submit Button -->
                                    <div class="col-12 text-center">
                                        <button id="change-password-button" type="submit" class="btn btn-danger">Create Company
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
