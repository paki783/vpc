<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Validator;
use Spatie\Permission\Models\Role;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\URL;

class RoleController extends Controller
{
    public function __construct()
    {
        // page action
        $this->add_product = 'admin/role/add';
        $this->store_product = 'admin/role/store';
        $this->view_product = 'admin/role/view';
        $this->edit_product = 'admin/role/edit';
        $this->update_product = 'admin/role/update';
        $this->delete_product = 'admin/role/delete';
        $this->detail_product = 'admin/role/detail';
        // page active
        $this->menu = "role-management";
        $this->sub_menu = "role";

        $this->add_page_product = "role/add";
        $this->edit_page_product = "role/edit";
        $this->view_page_product = "role/view";
        // image_upload_dir
        $this->images = "images";
        $this->dynamicFolder = "role";
        $this->image_upload_dir = "/images/role";
        // Page Heading
        $this->page_heading = "Role";

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
            "name" => "required|unique:roles,name",
        ]);

        if ($validator->fails()) {
            return json_encode(array("error" => $validator->errors()->first()));
        }
        $roleData['name'] = $input['name'];
        $roleData['guard_name'] = "web";
        $roleData['type'] = "admin";

        $role = Role::create($roleData);
        
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
        $res['data'] = Role::where('type', 'admin')->get();
        
        return view($this->view_page_product, $res);
    }

    public function edit($id)
    {
        if (isset($id) && $id != '') {
            $res_edit = Role::find($id);
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
            "id" => "required|exists:roles,id",
            "name" => "required|unique:roles,name," . $input['id'] . ",id",
        ]);

        if ($validator->fails()) {
            return json_encode(array("error" => $validator->errors()->first()));
        }

        $roleData['id'] = $input['id'];
        $roleData['name'] = $input['name'];
        $roleData['guard_name'] = "web";
        $roleData['type'] = "admin";

        $role = Role::find($roleData['id']);
        $role->update($roleData);
        
        return json_encode(array("success" => "Record Updated Successfully", "redirect" => URL::to($this->view_product), 'fieldsEmpty' => 'yes'));
    }

    public function delete($id)
    {
        if (isset($id) && $id != '') {
            $res = Role::find($id);
            if ($res) {
                
                $role = Role::find($id);

                foreach ($role->getPermissionNames() as $k2 => $v2) {
                    $role->revokePermissionTo($v2);
                }
                
                $role->delete();
                
                return json_encode(array("success" => "Record Succesfully Deleted", "deleteRow" => TRUE, "redirect" => URL::to($this->view_product)));
            } else {
                return json_encode(array("error" => $this->page_heading . " id is incorrect can't be deleted."));
            }
        } else {
            return json_encode(array("error" => $this->page_heading . " id is incorrect can't be deleted."));
        }
    }
}
