@extends('include.main')
@section('content')
	<section class="content-header">
		<div class="pull-left">
			<h1>
				{{ $title }}
			</h1>
		</div>
        <div class="pull-right">
            
        </div>
    </section>
	<section class="content">
        <div class="row">
            <div class="col-md-3">
                <div class="box box-primary">
                    <div class="box-body box-profile">
                        <img class="profile-user-img img-responsive img-circle" src="@if(!empty($data->profile_image)){{ $data->profile_image }}@endif" alt="User profile picture">
                        <h3 class="profile-username text-center">{{ ucwords($data->user_name) }}</h3>
                        <p class="text-muted text-center">Name: {{ ucwords($data->first_name) }} {{ ucwords($data->last_name) }}</p>
                        <ul class="list-group list-group-unbordered">
                            <li class="list-group-item">
                                <b>Email:</b> <a class="pull-right">{{ $data->email }}</a>
                            </li>
                            <li class="list-group-item">
                                <b>Facebook URL:</b> <a class="pull-right" href="{{ $data->facebook_link }}" target="_blanks">{{ $data->facebook_link }}</a>
                            </li>
                            <li class="list-group-item">
                                
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
            <div class="col-md-9">
                <div class="box box-primary">
                    <div class="box-header with-border">
                        <h3 class="box-title">All Ads</h3>
                    </div>
                    <div class="box-body">
                        
                    </div>
                </div>
            </div>
        </div>
	</section>
@endsection