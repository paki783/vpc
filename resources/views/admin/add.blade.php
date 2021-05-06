@extends('include.main')
@section('content')
	<section class="content-header">
		<div class="pull-left">
			<h1>
				{{ $title }}
			</h1>
		</div>
	</section>
	<section class="content">
	<div class="row">
		<div class="col-xs-12">
        <form autocomplete="off" class="validate ajaxForm" action="{{ url('admin/superusers/saveUser') }}" method="post" enctype="multipart/form-data">
			<div class="box">
			    <!-- /.box-header -->
			    <div class="box-body">
                    @include("include.message")
                    <div class="form-group">
                        <label>First Name</label>
                        <input type="text" class="form-control" required name="first_name" value="" />
                    </div>
                    <div class="form-group">
                        <label>Last Name</label>
                        <input type="text" class="form-control" required name="last_name" value="" />
                    </div>
                    <div class="form-group">
                        <label>Email Address</label>
                        <input type="text" class="form-control" required name="email" value="" />
                    </div>
                    <div class="form-group">
                        <label>Password</label>
                        <input type="password" class="form-control" id="password" required name="password" />
                    </div>
                    <div class="form-group">
                        <label>Confirm Password</label>
                        <input type="password" class="form-control" required name="confirm_password" />
                    </div>
                    <div class="form-group">
                        <label>Select Status</label>
                        <select class="form-control" id="status" name="status">
                            <option value="enabled">Enabled</option>
                            <option value="disabled">Disabled</option>
                        </select>
                    </div>
                </div>
                <div class="box-footer">
                    <input type="hidden" name="_token" value="{{ csrf_token() }}" />
                    <input type="submit" class="btn btn-primary" value="Save User" />
                </div>
            </div>
        </form>
		</div>
	</div>
</section>
@include('include.filterjs')

<script>
$(document).ready(function(){
    $("form.validate").validate({
      rules: {
        user_name:{
          required: true
        },
        first_name : {
            required : true,
        },
        last_name : {
            required : true,
        },
        email : {
            email: true,
            required : true,
        },
        password : {
            required : true,
        },
        confirm_password: {
            required: true,
            equalTo : "#password"
        },
      }, 
      messages: {
        user_name: "This field is required.",
        first_name : "This field is required.",
        last_name : "This field is required.",
        email : "This field is required.",
        password : "This field is required.",
        confirm_password  : "This field is required.",
      },
      invalidHandler: function (event, validator) {
        //display error alert on form submit    
        },
        errorPlacement: function (label, element) { // render error placement for each input type   
          var icon = $(element).parent('.input-with-icon').children('i');
            icon.removeClass('fa fa-check').addClass('fa fa-exclamation');  

          $('<span class="error"></span>').insertAfter(element).append(label);
          var parent = $(element).parent('.input-with-icon');
          parent.removeClass('success-control').addClass('error-control');  
        },
        highlight: function (element) { // hightlight error inputs
          var icon = $(element).parent('.input-with-icon').children('i');
            icon.removeClass('fa fa-check').addClass('fa fa-exclamation');  

          var parent = $(element).parent();
          parent.removeClass('success-control').addClass('error-control'); 
        },
        unhighlight: function (element) { // revert the change done by hightlight
          var icon = $(element).parent('.input-with-icon').children('i');
      icon.removeClass("fa fa-exclamation").addClass('fa fa-check');

          var parent = $(element).parent();
          parent.removeClass('error-control').addClass('success-control'); 
        },
        success: function (label, element) {
          var icon = $(element).parent('.input-with-icon').children('i');
      icon.removeClass("fa fa-exclamation").addClass('fa fa-check');

          var parent = $(element).parent('.input-with-icon');
          parent.removeClass('error-control').addClass('success-control');

          
        }
        // submitHandler: function (form) {

        // }
      });
});
</script>
@endsection