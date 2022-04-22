<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use App\Models\Organization;
use Carbon\Carbon;

class OrganizationController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
       
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {

        $request->validate([
            'name'=>'required',
            'description'=>'nullable',
            'abbreviation'=>'required',
            'registration_number'=>'required',
            'organization_first_registered'=>'required',
            'adviser'=>'required',
        ]);
        $chec2 = Organization::where('registration_number', $request->registration_number)->where('category_id',$request->category_id)->first();
       
        if(isset($chec2)){
            return response()->json(['errors'=>['registration_number' => ['The registration number has already been taken.']]], 422);
        }
        $chec = Organization::where('name', $request->name)->where('category_id',$request->category_id)->first();
       
        if(isset($chec)){
            return response()->json(['errors'=>['name' => ['The name has already been taken.']]], 422);
        }

        $org = Organization::create([
            'name'=>$request->name,
            'description'=>$request->description,
            'abbreviation'=>$request->abbreviation,
            'registration_number'=>$request->registration_number,
            'organization_first_registered'=> Carbon::parse($request->organization_first_registered)->format('Y-m-d'),
            'adviser'=>$request->adviser,
            'abbreviation'=>$request->abbreviation,
            'category_id'=>$request->category_id,
            'user_id'=>Auth::id()
        ]);
        return response()->json($org, 200);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(Request $request, $id)
    {
        $columns = ['created_at', 'department', 'id'];
        $length = $request->length;
        $column = $request->column;
        $dir = $request->dir;
        $archive = $request->archive;
        $searchValue = $request->search;
        if($request->filter == 0){
            $query = Organization::where('deleted', $archive)->where('category_id',$id)->orderBy($columns[$column], $dir);
        }else if($request->filter == 1){
            $query = Organization::where('deleted', $archive)->where('accredited', 1)->where('category_id',$id)->orderBy($columns[$column], $dir);                      
        }else{
            $query = Organization::where('deleted', $archive)->where('accredited', 0)->where('category_id',$id)->orderBy($columns[$column], $dir);
        }
       
    
        if($searchValue){
            $query->where(function($query) use ($searchValue){
                $query->where('description', 'like', '%'.$searchValue.'%');
            });
        }
        $projects = $query->paginate($length);
        return ['data'=>$projects, 'draw'=> $request->draw];
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $request->validate([
            'registration_number'=>'required',
            'name'=>'required',
            'organization_first_registered'=>'required',
            'adviser'=>'required',
            'abbreviation' => 'required'
        ]);

        $org = Organization::find($id);
        $org->registration_number = $request->registration_number;
        $org->name = $request->name;
        $org->organization_first_registered = Carbon::parse($request->organization_first_registered)->format('Y-m-d');
        $org->adviser = $request->adviser;
        $org->description = $request->description;
        $org->abbreviation = $request->abbreviation;
        $org->save();
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
       $org = Organization::find($id);
       $org->deleted = 1;
       $org->save();
       return response()->json([], 200);
    }

    public function getIndOrg($id){
       $org =  Organization::find($id);

       return response()->json($org, 200);
    }

    public function restore($id){
        $categ = Organization::find($id);
        $categ->deleted = 0;
        $categ->save();
        return response()->json([], 200);
    }

    public function accredit(Request $request, $id){
        $categ = Organization::find($id);
        $categ->accredited = $request->accredited;
        $categ->save();
        return response()->json([], 200);
    }
}
