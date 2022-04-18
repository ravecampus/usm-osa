<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class ProfileController extends Controller
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
        $request->validate([
            'fname'=>'required',
            'lname' =>'required',
            'email'=>'required'
        ]);
        $user = User::find($id);
        $user->fname = $request->fname;
        $user->lname = $request->lname;
        $user->email = $request->email;
        $user->mobile_number = $request->mobile_number;
        $user->address = $request->address;
        $user->gender = $request->gender;
        $user->position = $request->position;
        $user->save();
        return response()->json($user, 200);
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

    public function changePassword(Request $request){
   
        $user = User::find(auth()->user()->id);
        if(!Hash::check($request->current_password, $user->password)){
            $errors = ['errors'=>['current_password' => ['Invalid current password!']]];
            return response()->json($errors, 422);
        }
        return response()->json([], 200);
    }

    public function newPassword(Request $request){
        $request->validate([
            'password' => 'required|string|min:6|confirmed',
        ]);

        $user = User::find(auth()->user()->id);
        $user->password =  Hash::make($request->password);
        $user->save();
        $credentials = [
            'email' => $user->email,
            'password' => $request->password,
        ];
        // if (Auth::attempt($credentials)) {
            return response()->json($user,200);
        // }
    
    }

    public function users(Request $request){
        $request->validate([
            'fname'=>'required',
            'lname'=>'required',
            'gender'=>'required',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:6|confirmed',
        ]);

        $user = User::create([
            'fname' => $request->fname,
            'lname' => $request->lname,
            'gender' => $request->gender,
            'email' =>$request->email,
            'password' => Hash::make($request->password)
        ]);
        return response()->json($user, 200);
    }

    public function editUsers(Request $request, $id){
        $request->validate([
            'fname'=>'required',
            'lname'=>'required',
            'gender'=>'required',
            'email' => 'required|string|email',
        ]);

        $user = User::find($id);
        $user->fname  = $request->fname;
        $user->lname = $request->lname;
        $user->gender = $request->gender;
        $user->mobile_number = $request->mobile_number;
        $user->address = $request->address;
        $user->position = $request->position;
        $user->email = $request->email;
        $user->save();

        return response()->json($user, 200);
    }

    public function getUsers(Request $request){
       
        $columns = ['created_at', 'description', 'abbreviation', 'id'];
        $length = $request->length;
        $column = $request->column;
        $dir = $request->dir;
        $searchValue = $request->search;
        $query = User::orderBy($columns[$column], $dir);
    
        if($searchValue){
            $query->where(function($query) use ($searchValue){
                $query->where('fname', 'like', '%'.$searchValue.'%')
                ->orWhere('lname', 'like', '%'.$searchValue.'%');
            });
        }
        $projects = $query->paginate($length);
        return ['data'=>$projects, 'draw'=> $request->draw]; 
    }

    public function userPassword(Request $request,$id){
        $request->validate([
            'password' => 'required|string|min:6|confirmed',
        ]);

        $user = User::find($id);
        $user->password =  Hash::make($request->password);
        $user->save();
        $credentials = [
            'email' => $user->email,
            'password' => $request->password,
        ];
        // if (Auth::attempt($credentials)) {
            return response()->json($user,200);
        // }
    
    }
}
