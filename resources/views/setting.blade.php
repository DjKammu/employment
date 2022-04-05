@extends('layouts.admin-app')

@section('title', 'Setting')

@section('content')

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


            <div class="card-body">
              <div class="row mb-2">
                    <div class="col-6">
                        <h4 class="mt-0 text-left">Setting</h4>
                    </div>
                </div>

               <div class="row">
                        <div class="col-md-12">
                            <div class="card-body">
                                <form   method="post" 
                              action="{{ route('setting.store') }}" enctype="multipart/form-data">
                                  @csrf

                                    <!-- Current Password -->
                                    <div class="row">
                                        <div class="col-lg-6 col-md-6">
                                            <div class="form-group">
                                                <label class="text-dark" for="password">Client Id 
                                                </label>
                                                <input  name="client_id" value="{{@$setting->where('name','client_id')->pluck('value')->first()}}" type="text" class="form-control" placeholder="Client Id" required="">
                                            </div>
                                        </div> 

                                           <div class="col-lg-6 col-md-6">
                                            <div class="form-group">
                                                <label class="text-dark" for="password">Client Secret 
                                                </label>

                                                <input  name="client_secret" value="{{@$setting->where('name','client_secret')->pluck('value')->first()}}" type="text" class="form-control" placeholder="Client Secret">
                                            </div>
                                        </div>
                                        
                                    
                                       
                                       
                                    </div>


                                    <!-- Submit Button -->
                                    <div class="col-12 text-center">
                                        <button id="change-password-button" type="submit" class="btn btn-danger">Update Setting
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
