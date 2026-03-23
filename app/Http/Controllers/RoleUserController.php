<?php

namespace App\Http\Controllers;

use App\Models\User;

use Illuminate\Http\Request;

use Spatie\Permission\Models\Role;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class RoleUserController extends Controller
{
        /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $users = User::latest()->paginate(10);
        return view('admin.users.list',compact('users'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $roles = Role::orderBy('name', 'ASC')->get();
        return view('admin.users.create',[
            'roles' => $roles
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(),[
            'name' => 'required|min:3',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|min:3|same:confirm_password',
            'confirm_password' => 'required',
        ]);

        if($validator->passes()){
            $user = new User();
            $user->name = $request->name;
            $user->email = $request->email;
            $user->password = Hash::make($request->password);
            $user->save();
            $user->syncRoles($request->role);

            return redirect()->route('users.index')->with('success','User Successfully Created!');
        }else{
            return redirect()->back()->withInput()->withErrors($validator);
        }

    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $user = User::findOrFail($id);
        $roles = Role::orderBy('name', 'ASC')->get();
        $hasRoles = $user->roles->pluck('id');
        return view('admin.users.edit', [
            'user'=>$user,
            'roles'=>$roles,
            'hasRoles' => $hasRoles
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $user = User::findOrFail($id);

        $validator = Validator::make($request->all(),[
            'name' => 'required|min:3',
            'email' => 'required|email|unique:users,email,'.$id.',id',

        ]);

        if($validator->passes()){
            $user->name = $request->name;
            $user->email = $request->email;
            $user->save();
            $user->syncRoles($request->role);

            return redirect()->route('users.index')->with('success','user Successfully Updated!');
        }else{
            return redirect()->back()->withInput()->withErrors($validator);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Request $request)
    {
        $id = $request->id;
        $user = User::find($id);

        if($user == null){
            session()->flash('error','user not found!');
            return response()->json([
                'status' => false
            ]);
        }

        $user->delete();
        session()->flash('success','user Deleted Successfully!');
        return response()->json([
            'status' => true
        ]);
    }
}



