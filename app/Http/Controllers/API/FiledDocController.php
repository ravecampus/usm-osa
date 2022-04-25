<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Auth;
use App\Models\FiledDoc;
use App\Models\Upload;
use Carbon\Carbon;
use Illuminate\Support\Str;



class FiledDocController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request, $id)
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
            'filename'=>'required',
            'year'=>'required|digits:4|max:2050',
            'semester'=>'required'
        ]);

        $chec = FiledDoc::where('filename', $request->filename)->where('org_id',$request->org_id)->first();
       
        if(isset($chec)){
            return response()->json(['errors'=>['filename' => ['The filename has already been taken.']]], 422);
        }
        $user_id = Auth::id();
     
        // Str::random(8);
        if($request->hasFile('file')){
            $fileD = FiledDoc::create([
                'filename'=>$request->filename,
                'description'=>$request->description,
                'year'=>$request->year,
                'semester'=>$request->semester,
                'date_filed' => Carbon::today(),
                'user_id' =>$user_id,
                'org_id'=>$request->org_id
    
            ]);

            $files = $request->file('file');
            foreach ($files as $file) {

                $filename = $file->getClientOriginalName();
                $ext = $file->extension();
                $ran = Str::random(8);
                $name = now()->timestamp.".{$ran}.".$file->extension();
                $orgn = explode('.', $filename)[0];
                $path = $file->storeAs('files', $name, 'public');
                Upload::create([
                    'link'=> $name,
                    'slug'=>$ran,
                    'original_name'=>$orgn,
                    'extension'=>$ext,
                    'user_id'=>$user_id,
                    'file_id'=>$fileD->id
                ]);
            }
            return response()->json($fileD,200);
        }else{
            return response()->json(['errors'=>['file' => ['The field File is required.']]], 422);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(Request $request, $id)
    {
        $columns = ['year', 'filename', 'description', 'id'];
        $length = $request->length;
        $column = $request->column;
        $dir = $request->dir;
        $searchValue = $request->search;
        $semester = $request->semester;
        $year = $request->year;
        if($year == 0){
            $query = FiledDoc::with('files')->where('semester', $semester)->where('org_id', $id)->orderBy($columns[$column], $dir);
        }else{
            $query = FiledDoc::with('files')->where('year', $year)->where('semester', $semester)->where('org_id', $id)->orderBy($columns[$column], $dir);
        }
    
        if($searchValue){
            $query->where(function($query) use ($searchValue){
                $query->where('filename', 'like', '%'.$searchValue.'%')
                ->orWhere('description', 'like', '%'.$searchValue.'%');
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
            'filename'=>'required',
            'year'=>'required|digits:4',
            'semester'=>'required'
        ]);

        $file = FiledDoc::find($id);
        $file->filename = $request->filename;
        $file->year = $request->year;
        $file->semester = $request->semester;
        $file->description = $request->description;
        $file->save();
        return response()->json($file, 200);
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

}
