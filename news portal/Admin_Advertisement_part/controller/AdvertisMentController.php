<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\AddPosition;
use Illuminate\Http\Request;
use App\Models\Advertisment;
use App\Models\Pages;
use App\Models\settings\Category;
use App\Models\settings\Section;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Validator;
class AdvertisMentController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public $user;
    public function __construct(){
        $this->middleware(function($request,$next){
            $this->user = Auth::guard('web')->user();
            return $next($request);
        }) ;
    }
    public function index()
    {
       if(is_null($this->user) || !$this->user->can('advertisement.view')){
            abort('401', 'Unauthorized to access page');
        }
        $adds     = Advertisment::all();

        return view('admin.pages.advertisment.index',compact('adds'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */

    public function create()
    {
        if(is_null($this->user) || !$this->user->can('advertisement.create')){
            abort('401', 'Unauthorized to access page');
        }
        $adds = Advertisment::all()->count();
        $sort_order = ($adds > 0) ? $adds + 1 : 1;
        $positions  = AddPosition::orderByDesc('updated_at')->get();
        $sections   = Section::whereIn('id',$positions->pluck('section_id')->where('parent',0)->toArray())->orderBy('sort_order')->get();
        $pages      = Pages::all();
        $categories = Category::with('parentCategories.children')->orderByDesc('id')->get();
        $categories = getCategories($categories);
        return view('admin.pages.advertisment.create',compact('sort_order','categories','positions','sections','pages'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //   return $ad =Advertisment::orderBy("id")
        //   ->whereTime("start_time",">=" ,$request->start_time)
        //   ->whereTime("end_time","<=" ,$request->end_time)
        //   ->get();
        //     if(!empty($request->start_date) && !empty($request->end_date)){
        //         $ad = $ad->where('start_date', '<=', $request->start_date)
        //         ->where('end_date', '>=',$request->end_date);
        //     }
        //     if(!empty($request->start_time) && !empty($request->end_time)){
        //         $start_time =  date('H:i:s',strtotime($request->start_time));
        //         $end_time   =  date('H:i:s',strtotime($request->end_time));
        //         //dd($start_time);
        //        return $ad = $ad->where('start_time', '>=', $start_time)
        //         ->Where('end_time', '<=',$end_time)->get();
        //        //dd($start_time,$ad);
        //     }
        //dd($request->all());
        if($request->type == "Image"){
            $validator = Validator::make($request->all(), [
                'position'                 => 'required',
                'name'                     => 'required',
                //'script'                   => 'required',
                'image'                    => 'required|mimes:jpg,pdf,jpeg,png,bmp,tiff',
                //'link'                     => 'required',
                //'fav_image'                =>'mimes:ico'
            ]);
        }else{
            $validator = Validator::make($request->all(), [
                'position'                 => 'required',
                'name'                     => 'required',
                'script'                   => 'required',
                //'image'                    => 'required',
                //'link'                     => 'required',
                //'fav_image'                =>'mimes:ico'
            ]);
        }

          if ($validator->passes()) {

            $ad =Advertisment::orderBy("id");
            if(!empty($request->start_date) && !empty($request->end_date)){
                $ad = $ad->whereDate('start_date', '>=', $request->start_date)
                ->whereDate('end_date', '<=',$request->end_date);
            }
            if(!empty($request->start_time) && !empty($request->end_time)){
                $start_time =  date('H:i:s',strtotime($request->start_time));
                $end_time   =  date('H:i:s',strtotime($request->end_time));
                $ad = $ad->whereTime('end_time', '>=', $end_time);

            }
            if(!empty($request->page_name)){
                $ad = $ad->where('page_name',$request->page_name);
            }
            if(!empty($request->category_id)){
                $ad = $ad->where('category_id',$request->category_id);
            }
            if(!empty($request->position)){
                $ad = $ad->where('position',$request->position);
            }
             $add = $ad->get();

            if(count($add)>0)
            {
                return response()->json(['error' => 'Advertisment cant created successfully!']);
            }

            else{
            $position = AddPosition::with('scetion')->where('id',$request->position)->first();
            //dd($request->all());
            $addvertisment = new Advertisment();
            $addvertisment->position     = $request->position;
            $addvertisment->section_id   = $request->section_id;
            $addvertisment->page_name    = $request->page_name;
            $addvertisment->name         = $request->name;
            $addvertisment->category_id  = $request->category_id;
            $addvertisment->type         = $request->type;
            $addvertisment->script       = $request->script;
            $addvertisment->link         = $request->link;
            $addvertisment->sort_order   = $request->sort_order;
            $addvertisment->status       = $request->status;
            $addvertisment->start_date   = $request->start_date;
            $addvertisment->end_date     = $request->end_date;
            $addvertisment->start_time   = Date("H:i:s", strtotime($request->start_time));
            $addvertisment->end_time     = Date("H:i:s", strtotime($request->end_time));
           // dd($addvertisment);
            $addvertisment->update_time  = Carbon::now();
            $addvertisment->update_by    = Auth::user()->id;

            if ($request->file('image')) {
                $file               = $request->file('image');
                $filename           = Str::random(10) . '.' . $file->getClientOriginalExtension();
                $destinationPath    = base_path() . '/public/admin/advertisment';
                $file->move($destinationPath,$filename);
                $addvertisment->image =  $filename;
            }

            $addvertisment->save();
            return response()->json(['success' => 'Advertisment created successfully!']);

        }
    }

            return response()->json(['errors' => $validator->errors()]);
    }


    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        if(is_null($this->user) || !$this->user->can('advertisement.edit')){
            abort('401', 'Unauthorized to access page');
        }
        $addvertisment = Advertisment::findOrFail($id);
        $positions  = AddPosition::orderByDesc('updated_at')->get();
        $sections   = Section::whereIn('id',$positions->pluck('section_id')->where('parent',0)->toArray())->orderBy('sort_order')->get();
        $pages      = Pages::all();
        $categories = Category::with('parentCategories.children')->orderByDesc('id')->get();
        $categories = getCategories($categories);
        return view('admin.pages.advertisment.edit',compact('addvertisment','categories','positions','sections','pages'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function position(){
        $positions = [
            'Header Top Right' => 'Header Top Right (728x90)',
            'Featured Bottom' =>  'Featured Bottom (728x90)',
            'Economics Bottom'    => 'Economics Bottom (728x90)',
            'Entertainment Bottom' => 'Entertainment Bottom (728x90)',
            'Sidebar Top' => 'Sidebar Top (W:255 H:Any)',
            'Sidebar Online Vote Top' => 'Sidebar Online Vote Top (W:255 H:Any)',
            'Subscribe Bottom' => 'Subscribe Bottom (W:255 H:Any)',
            'Article Page Top' => 'Article Page Top (728x90)',
            'Article Page Bottom' => 'Article Page Bottom (728x90)',
            'Article Page Sidebar' => 'Article Page Sidebar (W:360 H:Any)',
            'Category Page Top' => 'Category Page Top',
            'Category Page Top' => 'Category Page Top',
            'Category Page Top' => 'Category Page Top',
            'M Home Page F2' => 'M Home Page F2',
            'M Home Page F3' => 'M Home Page F3',
            'M Category Page F1' => 'M Category Page F1',
            'M Category Page F2' => 'M Category Page F2',
            'M Article Page F1' => 'M Article Page F1',
            'M Article Page F2' => 'M Article Page F2',
            'M Footer Fixed' => 'M Footer Fixed',
        ];

        return $positions;
    }
    public function update(Request $request, $id)
    {
        //return $id;


        $validator = Validator::make($request->all(), [
            'position'                 => 'required',
            'name'                     => 'required',
            //'script'                   => 'required',
            //'image'                    => 'required',
            //'link'                     => 'required',
        ]);

        if ($validator->passes()) {

            $ad =Advertisment::orderBy("id");
            if(!empty($request->start_date) && !empty($request->end_date)){
                $ad = $ad->whereDate('start_date', '>=', $request->start_date)
                ->whereDate('end_date', '<=',$request->end_date);
            }
            if(!empty($request->start_time) && !empty($request->end_time)){
                $start_time =  date('H:i:s',strtotime($request->start_time));
                $end_time   =  date('H:i:s',strtotime($request->end_time));
                $ad = $ad->whereTime('end_time', '>=', $end_time);

            }
            if(!empty($request->page_name)){
                $ad = $ad->where('page_name',$request->page_name);
            }
            if(!empty($request->category_id)){
                $ad = $ad->where('category_id',$request->category_id);
            }
            if(!empty($request->position)){
                $ad = $ad->where('position',$request->position);
            }

            $add = $ad->get()->where('id', '!=' ,$id);

            if(count($add)>0)
            {
                return response()->json(['error' => 'Advertisment cant created successfully!']);
            }

            else{
            $position = AddPosition::with('scetion')->where('id',$request->position)->first();
            //dd($request->all());
            $addvertisment  =Advertisment::find($id);
            $addvertisment->position     = $request->position;
            $addvertisment->section_id   = $request->section_id;
            $addvertisment->page_name    = $request->page_name;
            $addvertisment->name         = $request->name;
            $addvertisment->category_id  = $request->category_id;
            $addvertisment->type         = $request->type;
            $addvertisment->script       = $request->script;
            $addvertisment->link         = $request->link;
            $addvertisment->sort_order   = $request->sort_order;
            $addvertisment->status       = $request->status;
            $addvertisment->start_date   = $request->start_date;
            $addvertisment->end_date     = $request->end_date;
            $addvertisment->start_time   = Date("H:i:s", strtotime($request->start_time));
            $addvertisment->end_time     = Date("H:i:s", strtotime($request->end_time));
           // dd($addvertisment);
            $addvertisment->update_time  = Carbon::now();
            $addvertisment->update_by    = Auth::user()->id;

            if ($request->file('image')) {
                $file               = $request->file('image');
                $filename           = Str::random(10) . '.' . $file->getClientOriginalExtension();
                $destinationPath    = base_path() . '/public/admin/advertisment';
                $file->move($destinationPath,$filename);
                $addvertisment->image =  $filename;
            }

            $addvertisment->save();
            return response()->json(['success' => 'Advertisment created successfully!']);
        }

        return response()->json(['errors' => $validator->errors()]);
     }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        if(is_null($this->user) || !$this->user->can('advertisement.delete')){
            abort('401', 'Unauthorized to access page');
        }
        Advertisment::findOrFail($id)->delete();
        return back();
    }
}
