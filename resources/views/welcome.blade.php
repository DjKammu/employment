@extends('layouts.frontend-app')

@section('content')
   
  <div class="col-lg-4 col-md-6 ml-auto mr-auto text-center"> 
     <h5>QPMENTERPRISES EMPLOYMENT</h5>
  </div>

  <div class="col-lg-4 col-md-6 ml-auto mr-auto">

             @if ($errors->any())
               <div class="alert alert-warning alert-dismissible fade show">
                 <button type="button" class="close" data-dismiss="alert">&times;</button>
                  <strong>Error!</strong>  
                   {{implode(',',$errors->all() )}}
                </div>
             @endif

      <form class="form"  action="{{ route('code') }}">
     
        <div class="card card-login">
            <div class="card-header ">
                <div class="card-header ">
                    <h3 class="header text-center">Enter Code</h3>
                </div>
            </div>
            <div class="card-body ">
                <div id="alert-container"></div>
                <div class="input-group">
                    <div class="input-group-prepend">
                        <span class="input-group-text">
                            <i class="fa fa-code"></i>
                        </span>
                    </div>
                    <input id="code" type="text" class="form-control" name="code" id="idInput" value="{{ old('code') }}" placeholder="Code" required>
                   
                </div>
              
                
            </div>
            <div class="card-footer ">
                <button type="submit" class="btn btn-warning btn-round btn-block mb-3">Enter</button>
                <div class="form-group text-center">
                     Click here to Admin
                    <a href="{{ route('login') }}" class="text-muted">Log In</a>
                </div>
            </div>
             
        </div>
    </form>

 </div> 



@endsection

@section('pagescript')
 <style type="text/css">
     .main-panel.home{
        width: 100%;
        height: 100%;
     }
 </style>
@endsection