@extends('layouts.frontend-app')

@section('content')
   
  <div class="col-lg-12 col-md-12 ml-auto mr-auto text-center"> 
     <h5>WELCOME TO {{ Str::upper(@$codes->first()->company->nick_name) }} PORTAL</h5>
  </div>

  <div class="col-lg-12 col-md-12 ml-auto mr-auto">

    <div class="table-responsive">
    <table id="project-types-table" class="table table-hover text-center">
        <thead>
        <tr class="text-danger">
            <th>No.</th>
            <th>Templates</th>
        </tr>
        </thead>
        <tbody>
          @foreach($codes as $index => $code)
         <tr>
           <td> {{$index + $codes->firstItem()}}  </td>
           <!-- <td>{{ $code->company_nick_name }}</td> -->
           <td><a href="{{ route('template',@$code->form_link) }}" target="_blank"> {{ (@$code->title) ? @$code->title : @$code->company->name }} </a></td>
        
          <td>
             
           </td>
         </tr> 
         @endforeach
        <!-- Project Types Go Here -->
        </tbody>
    </table>
</div>

  {!! $codes->render() !!}

 </div> 

    <div class="card-footer ">
        <div class="form-group text-center">
           Back to 
            <a href="{{ url('/') }}" class="text-muted">Home</a>
    
             Click here to Admin
            <a href="{{ route('login') }}" class="text-muted">Log In</a>
        </div>
    </div>
     
    
@endsection

@section('pagescript')

<style type="text/css">
     .main-panel.home{
        width: 100%;
        height: 100%;
     }
     .card-footer{
        position: fixed;
        bottom: 0px;
        width: 100%;
     }
</style>

@endsection