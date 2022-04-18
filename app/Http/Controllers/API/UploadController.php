<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use App\Models\Upload;
use Carbon\Carbon;
use Illuminate\Support\Str;


class UploadController extends Controller
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
        $user_id = Auth::id();
        if($request->hasFile('file')){
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
                    'file_id'=>$request->file_id
                ]);
            }
            return response()->json([],200);
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
        $ups = Upload::find($id);
        $path = storage_path('app/public/files/').$ups->link;
        unlink($path);
        $ups->delete();
        return response()->json($ups, 200);
    }

    public function downloadFile($id){
        $ups = Upload::find($id);
        $path = storage_path('app/public/files/').$ups->link;
        return response()->download($path);
    }
}
