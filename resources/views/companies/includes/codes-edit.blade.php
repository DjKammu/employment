@extends('layouts.admin-app')

@section('title', 'Edit Code')

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
                        <h4 class="mt-0 text-left">Edit Code</h4>
                    </div>
                </div>

               <div class="row">
                        <div class="col-md-12">
                            <div class="card-body">
                                <form   method="post" action="{{ route('codes.update', request()->code ) }}" >
                                   <input type="hidden" name="_method" value="PUT">
                                  @csrf

                                    <!-- Current Password -->
                                      
                                 
                                    
                                     <div class="row">
                                      <div class="col-lg-5 col-md-6 mx-auto">
                                            <div class="form-group">
                                                <label class="text-dark" for="password">Code 
                                                </label>
                                                <input  name="code"  type="text" value="{{ @$code->code }}" class="form-control" placeholder="Code" required="">
                                            </div>
                                        </div>

                                    </div> 


                                    <div class="row">
                                      <div class="col-lg-5 col-md-6 mx-auto">
                                            <div class="form-group">
                                                <label class="text-dark" for="password">Company Nick Name 
                                                </label>
                                                <input  name="company_nick_name"  type="text" class="form-control" value="{{ @$code->company_nick_name }}" placeholder="Company Nick Name">
                                            </div>
                                        </div>

                                    </div> 

                                    <div class="row">
                                      <div class="col-lg-5 col-md-6 mx-auto">
                                            <div class="form-group">
                                                <label class="text-dark" for="password">Form Link
                                                </label>
                                                <input  name="form_link"  type="text" class="form-control" value="{{ @$code->form_link }}" placeholder="Form Link" required="">
                                            </div>
                                        </div>

                                    </div> 
 


                                    <!-- Submit Button -->
                                    <div class="col-12 text-center">
                                        <button id="change-password-button" type="submit" class="btn btn-danger">Update Holiday 
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