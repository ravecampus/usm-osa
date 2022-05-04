<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use App\Models\Organization;
use App\Models\Category;

class ReportController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
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
        //
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
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }

    public function org($id){
        $iid = $id;
        if($iid > 0){
            $org = Organization::where('accredited', 1)->where('category_id',$id)->where('deleted',0)->get();
        }else{
            $org = Organization::where('accredited', 1)->where('deleted',0)->get();
        }
        return response()->json($org, 200);
    }

    public function categories(){
        $org = Category::where('deleted',0)->get();
        return response()->json($org, 200);
    }

    public function filter(Request $request){
        $columns = ['name','registration_number','category_id','organization_first_registered','abbreviation'];
        $sort = $request->sort;
        $key = $request->key;
        $sem = $request->sem;
        $year = $request->yr;
        $category = $request->category;
        if($sem > 0 && $category > 0){
            $query = Organization::where('year', $year)->where('semester',$sem)->where('category_id',$category)->orderBy($columns[$sort], $key);
        }else if($sem > 0 && $category == 0){
            $query = Organization::where('year', $year)->where('semester',$sem)->orderBy($columns[$sort], $key);
        }else if($sem == 0 && $category > 0){
            $query = Organization::where('category_id',$category)->orderBy($columns[$sort], $key);
        }else{
            $query = Organization::orderBy($columns[$sort], $key);
        }
        $proj = $query->with('docs')->where('accredited', 1)->get();

      return response()->json($proj,200);


    }
}
