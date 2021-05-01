<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Validator;
use Spatie\Permission\Models\Permission;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\URL;

class PermissionController extends Controller
{
    public function __construct()
    {
        // page action
        $this->add_product = 'admin/permission/add';
        $this->store_product = 'admin/permission/store';
        $this->view_product = 'admin/permission/view';
        $this->edit_product = 'admin/permission/edit';
        $this->update_product = 'admin/permission/update';
        $this->delete_product = 'admin/permission/delete';
        $this->detail_product = 'admin/permission/detail';
        // page active
        $this->menu = "role-management";
        $this->sub_menu = "permission";

        $this->add_page_product = "permission/add";
        $this->edit_page_product = "permission/edit";
        $this->view_page_product = "permission/view";
        // image_upload_dir
        $this->images = "images";
        $this->dynamicFolder = "permission";
        $this->image_upload_dir = "/images/permission";
        // Page Heading
        $this->page_heading = "permission";

        $this->no_results_found = "user/no_results_found";
    }

    public function index()
    {
        Redirect::to($this->add_product)->send();
    }

    public function add()
    {
        $res['title'] = "Add New " . $this->page_heading;
        $res["page_title"] = "add";
        $res["page_heading"] = $this->page_heading;
        $res['active'] = $this->add_page_product;
        $res['menu'] = $this->menu;
        $res['sub_menu'] = $this->sub_menu;
        $res["add_product"] = $this->store_product;
        
        return view($this->add_page_product, $res);
    }

    public function store(Request $request)
    {
        $input = $request->all();
        $validator = Validator::make($request->all(), [
            "name" => "required|unique:permissions,name",
        ]);

        if ($validator->fails()) {
            return json_encode(array("error" => $validator->errors()->first()));
        }
        $permissionData['name'] = $input['name'];
        $permissionData['guard_name'] = "web";

        $permission = Permission::create($permissionData);
        
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
        $res['data'] = Permission::all();
        
        return view($this->view_page_product, $res);
    }

    public function edit($id)
    {
        if (isset($id) && $id != '') {
            $res_edit = Permission::find($id);
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
                // print_b($res);
                return view($this->add_page_product, $res);
            } else {
                Redirect::to($this->no_results_found)->send();
            }
        } else {
            Redirect::to($this->no_results_found)->send();
        }
    }
    
    public function update(Request $request)
    {
        $input = $request->all();
        $validator = Validator::make($request->all(), [
            "id" => "required|exists:permissions,id",
            "name" => "required|unique:permissions,name," . $input['id'] . ",id",
        ]);

        if ($validator->fails()) {
            return json_encode(array("error" => $validator->errors()->first()));
        }

        $permissionData['id'] = $input['id'];
        $permissionData['name'] = $input['name'];
        $permissionData['guard_name'] = "web";

        $permission = Permission::find($permissionData['id']);
        $permission->update($permissionData);
        
        return json_encode(array("success" => "Record Updated Successfully", "redirect" => URL::to($this->view_product), 'fieldsEmpty' => 'yes'));
    }

    public function delete($id)
    {
        if (isset($id) && $id != '') {
            $res = Permission::find($id);
            if ($res) {
                
                $permission = Permission::find($id);
                $permission->delete();
                
                return json_encode(array("success" => "Record Succesfully Deleted", "deleteRow" => TRUE, "redirect" => URL::to($this->view_product)));
            } else {
                return json_encode(array("error" => $this->page_heading . " id is incorrect can't be deleted."));
            }
        } else {
            return json_encode(array("error" => $this->page_heading . " id is incorrect can't be deleted."));
        }
    }
}
