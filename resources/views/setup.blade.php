@extends('layouts.admin-app')

@section('title', 'Set Up')

@section('content')
      <!-- Start Main View -->
  <div class="card p-2">
    <div class="row">
        <div class="col-md-12">

            <div class="card-body">
                <div class="row mb-2">
                    <div class="col-6">
                        <h4 class="mt-0 text-left">All Setup</h4>
                    </div>
                </div>

                <div class="row mb-2">
                    <div class="col-12">
                         <button type="button" class="btn btn-danger mt-0"  onclick="return window.location.href='{{url("users")}}'">Users
                        </button>
                        <button type="button" class="btn btn-danger mt-0"  onclick="return window.location.href='{{url("roles")}}'">Roles
                        </button> 

                        <button type="button" class="btn btn-danger mt-0"  onclick="return window.location.href='{{url("setting")}}'">Setting
                        </button>
                    </div>
                    <div class="col-12">
                      
                    </div>
                </div>

            </div>
        </div>
    </div>
</div>

@endsection

@section('pagescript')

<script type="text/javascript">

  $(document).ready(function(){

  $('#search').click(function(){
        var search = $('#inputSearch').val();

        if(!search){
         // alert('Please enter to search');
        }
        window.location.href = '?s='+search;
  });

  $(document).keyup(function(event) {
    if (event.keyCode === 13) {
        $("#search").click();
    }
});


  });

</script>
<style type="text/css">
  
span.cross{
    position: absolute;
    z-index: 10;
    left: 30px;
    display: none;
}
tr a:hover span.cross{
  display: block;
}
button.btn.btn-neutral.bg-transparent.btn-icon{
  background-color: transparent !important;
}
td{
  width: 100%;
}
</style>
@endsection