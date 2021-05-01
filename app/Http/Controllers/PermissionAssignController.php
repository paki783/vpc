<?php

namespace App\Http\Controllers;

use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;
use Illuminate\Http\Request;
use Validator;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\URL;

class PermissionAssignController extends Controller
{
    public function __construct()
    {
        // page action
        $this->add_product = 'admin/permission/assign/add';
        $this->store_product = 'admin/permission/assign/store';
        $this->view_product = 'admin/permission/assign/view';
        $this->edit_product = 'admin/permission/assign/edit';
        $this->update_product = 'admin/permission/assign/update';
        $this->delete_product = 'admin/permission/assign/delete';
        $this->detail_product = 'admin/permission/assign/detail';
        // page active
        $this->menu = "role-management";
        $this->sub_menu = "permission-assign";

        $this->add_page_product = "permission/assign/add";
        $this->edit_page_product = "permission/assign/edit";
        $this->view_page_product = "permission/assign/view";
        // image_upload_dir
        $this->images = "images";
        $this->dynamicFolder = "permission/assign";
        $this->image_upload_dir = "/images/permission/assign";
        // Page Heading
        $this->page_heading = "Permission Assign";

        $this->no_results_found = "user/no_results_found";
    }

    public function index()
    {
        Redirect::to($this->view_product)->send();
    }

    public function update(Request $request)
    {
        $input = $request->all();
        // dd($input);
        $validator = Validator::make($request->all(), [
            "role_id" => "required|exists:roles,id",
            "permission.*" => "required",
        ]);

        if ($validator->fails()) {
            return json_encode(array("error" => $validator->errors()->first()));
        }
        // dd($input);
        $role = Role::find($input['role_id']);
        $role->syncPermissions($input['permission']);
        
        return json_encode(array("success" => "Record Updated Successfully", "redirect" => URL::to($this->view_product), 'fieldsEmpty' => 'yes'));
    }

    public function view()
    {
        $res['title'] = "View All " . $this->page_heading;
        $res['active'] = $this->view_product;
        $res["page_heading"] = $this->page_heading;
        $res["add_product"] = $this->add_product;
        $res["edit_product"] = $this->edit_product;
        $res["delete_product"] = $this->delete_product;
        // $res["detail_product"] = $this->detail_product;
        $res['image_upload_dir'] = $this->image_upload_dir;
        $res['menu'] = $this->menu;
        $res['sub_menu'] = $this->sub_menu;
        $res['data'] = Role::where("type", "admin")->get();
        
        return view($this->view_page_product, $res);
    }
    
    public function edit($id)
    {
        if (isset($id) && $id != '') {
            $res_edit = Role::where('type', 'admin')->find($id);
            if ($res_edit) {
                $res["title"] = "Edit " . $this->page_heading;
                $res["page_title"] =  "";
                $res["page_heading"] = $this->page_heading;
                $res["active"] = $this->edit_page_product;
                $res["add_product"] = $this->update_product;
                $res['image_upload_dir'] = $this->image_upload_dir;
                $res['menu'] = $this->menu;
                $res['sub_menu'] = $this->sub_menu;
                $res['data'] = $res_edit;
                $res['permission'] = Permission::all();
                // print_b($res);
                return view($this->add_page_product, $res);
            } else {
                Redirect::to($this->no_results_found)->send();
            }
        } else {
            Redirect::to($this->no_results_found)->send();
        }
    }    
    
}
