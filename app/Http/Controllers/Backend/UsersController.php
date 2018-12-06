<?php

namespace App\Http\Controllers\Backend;

use App\Http\Requests\UserDestroyRequest;
use App\Http\Requests\UserStoreRequest;
use App\Http\Requests\UserUpdateRequest;
use App\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class UsersController extends BackendController
{
    public function index()
    {
        $users      = User::orderBy('name')->paginate($this->limit);
        $usersCount = User::count();

        return view("backend.users.index", compact('users', 'usersCount'));
    }

    public function create()
    {
        $user = new User();
        return view("backend.users.create", compact('user'));
    }

    public function store(UserStoreRequest $request)
    {
        //$data = $request->all();

        $user = User::create($request->all());
        $user->attachRole($request->role);

        //$data['password'] = bcrypt($data['password']);
        //User::create($data);
        return redirect("/backend/users")
            ->with("message", "New user was created successfully!");
    }

    public function edit($id)
    {
        $user = User::findOrFail($id);
        return view("backend.users.edit", compact('user'));
    }


    public function update(UserUpdateRequest $request, $id)
    {
        $user = User::findOrFail($id);
        $user->update($request->all());

        $user->detachRoles();
        $user->attachRole($request->role);


        return redirect("/backend/users")
            ->with("message", "User was updated successfully!");
    }

    public function confirm(UserDestroyRequest $request, $id)
    {
        $user = User::findOrFail($id);
        $users = User::where('id', '!=', $user->id)->pluck('name', 'id');

        return view("backend.users.confirm", compact('user', 'users'));
    }

    public function destroy(UserDestroyRequest $request, $id)
    {
        $user = User::findOrFail($id);

        $deleteOption = $request->delete_option;
        $selectedUser = $request->selected_user;

        if ($deleteOption == "delete") {
            // delete user posts
            $user->posts()->withTrashed()->forceDelete();
        }
        elseif ($deleteOption == "attribute") {
            $user->posts()->update(['author_id' => $selectedUser]);
        }

        $user->delete();

        return redirect("/backend/users")
            ->with("message", "User was deleted successfully!");
    }

}
