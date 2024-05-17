<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use App\Http\Traits\UploadFile;
use Illuminate\Support\Facades\DB;
use Spatie\Permission\Models\Role;
use App\Http\Requests\User\UserRequest;
use Yajra\DataTables\Facades\DataTables;
use App\Http\Requests\User\UserUpdateRequest;

class UserController extends Controller
{
    use UploadFile;

    public function getAll()
    {
        $users = User::with('file', 'roles')->get();
        return DataTables::of($users)->toJson();
    }

    public function getRoles()
    {
        $roles = Role::get();
        return response()->json(['categories' => $roles], 202);
    }

    public function index()
    {
        return view('Users.index');
    }

    public function store(UserRequest $request)
    {
        try {
            DB::beginTransaction();
            $user = new User($request->all());
            $user->save();
            $user->syncRoles([$request->role]);
            $this->uploadFile($user, $request);
            DB::commit();
            return response()->json([], 200);
        } catch (\Throwable $th) {
            DB::rollback();
            throw $th;
        }
    }

    public function show(User $user)
    {
        $user->load('file', 'roles');
        return response()->json(['user_data' => $user], 202);
    }

    public function update(UserUpdateRequest $request, User $user)
    {
        if (!$request->hasFile('file')) unset($request['file']);
        // dd($request);

        try {
            DB::beginTransaction();
            $user->update($request->all());

            $user->syncRoles([$request->role]);
            $this->uploadFile($user, $request);
            DB::commit();
            return response()->json([], 204);
        } catch (\Throwable $th) {
            DB::rollback();
            throw $th;
        }
    }

    public function destroy(User $user)
    {
        $user->delete();
        $this->deleteFile($user);
        return response()->json([], 204);
    }
}
