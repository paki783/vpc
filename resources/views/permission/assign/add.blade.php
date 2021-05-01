@extends('include.main')
@section('content')
  <style>
    .custom-checkbox .form-check-input{
      position: relative;
      top: 2px;
      margin-right: 5px;
    }
    .custom-checkbox .form-check-label{
      font-weight: 400;
    }
  </style>
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
      <form class="ajaxForm validate" action="{{ url($add_product) }}" method="post" enctype="multipart/form-data">
			  <div class="box">
			    <!-- /.box-header -->
          <div class="box-body">

            <div class="form-group">
              <label for="role_id">Role</label>
              <input type="text" class="form-control" value="<?= ucfirst(@$data->name); ?>" disabled="disabled"/>
            </div>

            <p><strong>Permission Assign</strong></p>
            <div class="row">
              <div class="col-md-12">
                  
                <div class="form-check custom-checkbox">
                  <input type="checkbox" class="form-check-input permission_all" id="permission_all">
                  <label class="form-check-label permission_all_text" for="permission_all">
                    Select All Checkbox
                  </label>
                </div>

              </div>
              <?php
                $getPermission = [];
                foreach ($data->getPermissionNames() as $k2 => $v2) {
                  $getPermission[] = $v2;
                }
                if (isset($permission) && count($permission) > 0) {
                  foreach ($permission as $k => $v) {
                    $checked = '';
                    if (in_array($v->name, $getPermission)) {
                      $checked = "checked='checked'";
                    }
              ?>
              <div class="col-md-6">
                  
                <div class="form-check custom-checkbox">
                  <input type="checkbox" class="form-check-input permission" id="permission<?= $v->id; ?>" name="permission[]" value="<?= $v->name; ?>" <?= $checked; ?>>
                  <label class="form-check-label" for="permission<?= $v->id; ?>">
                    <?= str_replace('-', ' ', ucfirst($v->name)); ?>
                  </label>
                </div>
              </div>
              <?php
                  }
                }
              ?>
            </div>

          </div>
          <div class="box-footer">
              <input type="hidden" name="_token" value="{{ csrf_token() }}" />
              <input type="hidden" name="role_id" value="<?= @$data->id; ?>"/>
              <button type="submit" class="btn btn-primary ajaxFormSubmitAlter">Submit</button>
          </div>
        </div>
      </form>
		</div>
	</div>
</section>

<script>
$(document).ready(function(){
  $('.permission_all').on('click', function() {
    
    if ($(this).is(':checked') == true) {
      $(".permission_all_text").text('Unselect All Checkbox');
    }else{
      $(".permission_all_text").text('Select All Checkbox');
    }
    $('.permission').prop('checked', $(this).prop("checked"));              
  });
  // $('.permission_all').toggle(function(){
  //     $('.permission').attr('checked','checked');
  //     $(this).val('uncheck all');
  // },function(){
  //     $('.permission').removeAttr('checked');
  //     $(this).val('check all');        
  // });

});
</script>
@endsection