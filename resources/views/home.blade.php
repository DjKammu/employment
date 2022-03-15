@extends('layouts.admin-app')

@section('title', 'Dashboard')

@section('content')

 <!-- Start Main View -->
                <!-- Dashboard Overview -->
<div class="row">


    <!-- Subcontractors Overview -->
    <div class="col-lg-4 col-md-6 col-sm-6">
        <div class="card card-stats">
            <div class="card-body ">
                <div class="row">
                    <div class="col-5 col-md-4">
                        <div class="icon-big text-center icon-warning">
                            <i class="fa fa-user-circle text-danger"></i>
                        </div>
                    </div>
                    <div class="col-7 col-md-8">
                        <div class="numbers">
                            <p class="card-category">Roles</p>
                            <p id="subcontractors_count" class="card-title">{{@$roles}}<p>
                        </div>
                    </div>
                </div>
            </div>
            <div class="card-footer ">
                <hr>
                <div class="stats">
                    <a href="{{ route('roles.index') }}" class="text-muted"><i class="fa fa-eye"></i> View All</a>
                </div>
            </div>
        </div>
    </div>
  
    <!-- Users Overview -->
    <div class="col-lg-4 col-md-6 col-sm-6">
        <div class="card card-stats">
            <div class="card-body ">
                <div class="row">
                    <div class="col-5 col-md-4">
                        <div class="icon-big text-center icon-warning">
                            <i class="fa fa-user text-success"></i>
                        </div>
                    </div>
                    <div class="col-7 col-md-8">
                        <div class="numbers">
                            <p class="card-category">Users</p>
                            <p id="users_count" class="card-title">{{@$users}}<p>
                        </div>
                    </div>
                </div>
            </div>
            <div class="card-footer ">
                <hr>
                <div class="stats">
                    <a href="{{ route('users.index')}}" class="text-muted"><i class="fa fa-eye"></i> View All</a>
                </div>
            </div>
        </div>
    </div>


</div>


@endsection
