<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreAdminRequest;
use App\Http\Requests\UpdateAdminRequest;
use App\Models\Admin;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use Inertia\Inertia;

class AdminAdminController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $admins = Admin::orderBy('id', 'desc')->get()->map(function ($user) {
            return collect($user)->only(['id', 'name', 'email', 'status', 'role']);
        });

        $can = [
            'create_user' => 'Quản trị' ==  Auth::user()->role,
            'update_user' => 'Quản trị' ==  Auth::user()->role,
            'delete_user' => 'Quản trị' ==  Auth::user()->role,
            'import_user' => 'Quản trị' ==  Auth::user()->role,
            'export_user' => 'Quản trị' ==  Auth::user()->role,
        ];

        return Inertia::render('AdminIndex', [
            'admins' => $admins,
            'can' => $can,
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreAdminRequest $request)
    {
        //Check authorize
        if ('Quản trị' != Auth::user()->role) {
            $request->session()->flash('message', 'Bạn không có quyền!');
            return redirect()->back()->withErrors('Bạn không có quyền!');
        }

        $admin = new Admin();

        $admin->name = $request->name;
        $admin->email = $request->email;
        $admin->status = $request->status;
        $admin->password = bcrypt($request->password);
        $admin->role = $request->role;
        $admin->save();

        $request->session()->flash('message', 'Tạo xong người quản trị!');
        return redirect()->route('admin.admins.index');
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
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateAdminRequest $request, Admin $admin)
    {
        //Check authorize
        if ('Quản trị' != Auth::user()->role) {
            $request->session()->flash('message', 'Bạn không có quyền!');
            return redirect()->back()->withErrors('Bạn không có quyền!');
        }

        $admin->name = $request->name;
        $admin->email = $request->email;
        $admin->status = $request->status;
        $admin->role = $request->role;
        $admin->save();

        $request->session()->flash('message', 'Sửa xong người quản trị!');
        return redirect()->route('admin.admins.index');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Admin $admin)
    {
        //Check authorize
        if ('Quản trị' != Auth::user()->role) {
            Session::flash('message', 'Bạn không có quyền!');
            return redirect()->back()->withErrors('Bạn không có quyền!');
        }

        $admin->delete();
        Session::flash('message', 'Xóa xong người quản trị!');
        return redirect()->route('admin.admins.index');
    }

    public function bulkDelete(Request $request)
    {
        //Check authorize
        if ('Quản trị' != Auth::user()->role) {
            $request->session()->flash('message', 'Bạn không có quyền!');
            return redirect()->back()->withErrors('Bạn không có quyền!');
        }

        $admins = $request->admins;
        foreach ($admins as $admin) {
            $deleted_admin = Admin::findOrFail($admin['id']);
            if ($deleted_admin) {
                $deleted_admin->destroy($deleted_admin->id);
            }
        }

        $request->session()->flash('message', 'Xóa xong người dùng!');
        return redirect()->route('admin.admins.index');
    }
}
