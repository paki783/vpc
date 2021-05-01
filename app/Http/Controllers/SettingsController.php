<?php

namespace App\Http\Controllers;

use App\Helpers\Helper;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Controller;
use Validator;
use App\Slider;
use App\News;
use App\Countries;
use App\Attachment;

class SettingsController extends Controller
{
    function sliderAll(Request $req){
        
        $data = Slider::latest()->all();
        $parse = [
            "menu" => "settings",
            "sub_menu" => "slider",
            "title" => "Sliders",
            'data' => $data,
        ];
        
        return view('settings.slider.all_slide', $parse);
    }
    function add_slide(Request $req){
        
        $data = [];
        $parse = [
            "menu" => "settings",
            "sub_menu" => "slider",
            "title" => "Add Slider",
            'data' => $data,
        ];
        
        return view('settings.slider.add_slide', $parse);
    }
    function saveSlide(Request $req){
        $ins["caption_one"] = empty($req->caption_one) ? "" : $req->caption_one;
        $ins["caption_two"] = empty($req->caption_two) ? "" : $req->caption_two;
        if($req->id == 0){
            $validator = Validator::make($req->all(), [
                'image' => 'required|file',
            ]);
            if ($validator->fails()) {
                return redirect()->back()->withErrors($validator->errors());
            }else{
                if($req->hasFile('image')){
                    $img = $req->file('image')->store('/', 'public');
                    $img = URL("public/storage/".$img);
                }
                $ins = [
                    "picture" => $img
                ];
                Slider::create($ins);
                $webmsg = [
                    "class" => "success",
                    "message" => "Slider added successfully",
                ];
                return redirect()->back()->with($webmsg);
            }
        }else{
            if($req->hasFile('image')){
                $img = $req->file('image')->store('/', 'public');
                $img = URL("public/storage/".$img);
            }
            $ins = [
                "picture" => $img
            ];
            Slider::where("id", $req->id)->update($ins);
            $webmsg = [
                "class" => "success",
                "message" => "Slider updated successfully",
            ];
            return redirect()->back()->with($webmsg);
        }
    }
    function edit(Request $req){
        
        $data = Slider::where("id", $req->id)->first();
        $parse = [
            "menu" => "settings",
            "sub_menu" => "slider",
            "title" => "Edit Slider",
            'data' => $data,
        ];
        
        return view('settings.slider.edit_slide', $parse);
    }
    function deleteSlide(Request $req){
        $id = $req->id;
        Slider::where("id", $id)->delete();
        $webmsg = [
            "class" => "success",
            "message" => "Slider deleted successfully",
        ];
        return redirect()->back()->with($webmsg);
    }
    function newsAll(){
        
        $data = News::all();
        $parse = [
            "menu" => "settings",
            "sub_menu" => "news",
            "title" => "News",
            'data' => $data,
        ];
        return view('settings.news.all', $parse);
    }
    function newsAdd(Request $req){
        $data = [];
        $parse = [
            "menu" => "settings",
            "sub_menu" => "news",
            "title" => "Add News",
            'data' => $data,
        ];
        return view('settings.news.add', $parse);
    }
    function newsEdit(Request $req){
        $data = News::where("id", $req->id)->first();
        $parse = [
            "menu" => "settings",
            "sub_menu" => "news",
            "title" => "Edit News",
            'data' => $data,
        ];
        return view('settings.news.edit', $parse);
    }
    function saveNews(Request $req){
        $id = $req->id;
        if($id == 0){
            $validator = Validator::make($req->all(), [
                'news_title' => 'required',
                'news_desc' => 'required',
                'image' => 'required',
            ]);
            if ($validator->fails()) {
                return redirect()->back()->withErrors($data->errors());
            }
            $params = request(['news_title', 'news_desc']);
            $img = "";
            if($req->hasFile('image')){
                $img = $req->file('image')->store('/', 'public');
                $img = URL("public/storage/".$img);
            }
            News::create([
                "title" => $req->news_title,
                "desc" => $req->news_desc,
                "image" => $img,
            ]);

            $webmsg = [
                "class" => "success",
                "message" => "News added successfully",
            ];
            return redirect()->back()->with($webmsg);
        }else{
            $validator = Validator::make($req->all(), [
                'news_title' => 'required',
                'news_desc' => 'required',

            ]);
            if ($validator->fails()) {
                return redirect()->back()->withErrors($validator->errors());
            }else{
                $ins = [
                    "title" => $req->news_title,
                    "desc" => $req->news_desc,
                ];
                if($req->hasFile('image')){
                    $img = $req->file('image')->store('/', 'public');
                    $img = URL("public/storage/".$img);
                    $ins["image"] = $img;
                }
                News::where("id", $id)->update($ins);
                $webmsg = [
                    "class" => "success",
                    "message" => "News updated successfully",
                ];
                return redirect()->back()->with($webmsg);
            }
        }
    }
    function deleteNews(Request $req){
        News::where("id", $req->id)->delete();
        $webmsg = [
            "class" => "success",
            "message" => "News deleted successfully",
        ];
        return redirect()->back()->with($webmsg);
    }
    function countries_all(Request $req){
        
        $data = Countries::paginate(15);
        
        $parse = [
            "menu" => "settings",
            "sub_menu" => "countries",
            "title" => "Countries",
            'data' => $data,
        ];


        return view('settings.countries.all_countries', $parse);
    }
    function countries_edit(Request $req){
        
        $data = Countries::where("id", $req->id)->first();
        $data->attachment = Attachment::where([
            "type_id" => $req->id,
            "type" => "country",
        ])->first();

        $parse = [
            "menu" => "settings",
            "sub_menu" => "countries",
            "title" => "Edit Country",
            'data' => $data,
            "regions" => Helper::regions(),
        ];
        
        return view('settings.countries.edit_countries', $parse);
    }
    function saveCountry(Request $req){
        $validator = Validator::make($req->all(), [
            'name' => 'required',
            'code' => 'required',
            'region' => 'required',
        ]);
        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator->errors());
        }else{
            $ins = [
                'name' => $req->name,
                'code' => $req->code,
                'region' => $req->region,
            ];
            if($req->id == 0){

            }else{
                Countries::where("id", $req->id)->update($ins);
                if($req->hasFile('flag')){
                    Attachment::where([
                        "type_id" => $req->id,
                        "type" => "country",
                    ])->delete();

                    $img = $req->file('flag')->store('/', 'public');
                    $img = URL("public/storage/".$img);

                    Attachment::create([
                        "type_id" => $req->id,
                        "type" => "country",
                        "photo" => $img,
                        "video_url" => "",
                        "model_name" => "country",
                    ]);
                }
                $webmsg = [
                    "class" => "success",
                    "message" => "Country updated succesfully",
                ];
                return redirect()->back()->with($webmsg);
            }
        }
    }
    function api_getcountry(Request $req){
        $data = Countries::get();
        return Helper::successResponse($data, 'success');
    }
}