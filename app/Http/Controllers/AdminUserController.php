<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreUserRequest;
use App\Http\Requests\UpdateUserRequest;
use App\Models\Supplier;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use Inertia\Inertia;

class AdminUserController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $users = User::orderBy('id', 'desc')->with('supplier')->get()->map(function ($user) {
            return [
                'id' => $user->id,
                'code' => $user->code,
                'name' => $user->name,
                'email' => $user->email,
                'status' => $user->status,
                'supplier' => $user->supplier->code . ' - ' . $user->supplier->name,
            ];
        });

        $can = [
            'create_user' => 'Quản trị' ==  Auth::user()->role->name,
            'update_user' => 'Quản trị' ==  Auth::user()->role->name,
            'delete_user' => 'Quản trị' ==  Auth::user()->role->name,
            'import_user' => 'Quản trị' ==  Auth::user()->role->name,
            'export_user' => 'Quản trị' ==  Auth::user()->role->name,
        ];

        $suppliers = Supplier::where('status', 'On')->orderBy('id', 'desc')->get()->map(function ($supplier) {
            return $supplier->code . ' - ' . $supplier->name;
        });

        return Inertia::render('UserIndex', [
            'users' => $users,
            'can' => $can,
            'suppliers' => $suppliers,
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
    public function store(StoreUserRequest $request)
    {
        //Check authorize
        if ('Quản trị' != Auth::user()->role->name) {
            $request->session()->flash('message', 'Bạn không có quyền!');
            return redirect()->back()->withErrors('Bạn không có quyền!');
        }

        $user = new User();

        $user->name = $request->name;
        $user->email = $request->email;
        $user->status = $request->status;
        $user->password = bcrypt($request->password);

        $supplier_arr = explode(' - ', $request->supplier);
        $supplier_code = $supplier_arr[0];
        $supplier_name   = $supplier_arr[1];
        $supplier = Supplier::where('code', $supplier_code)->where('name', $supplier_name)->first();

        $user->supplier_id = $supplier->id;
        $user->save();

        $request->session()->flash('message', 'Tạo xong người dùng!');
        return redirect()->route('admin.users.index');
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
    public function update(UpdateUserRequest $request, User $user)
    {
        //Check authorize
        if ('Quản trị' != Auth::user()->role->name) {
            $request->session()->flash('message', 'Bạn không có quyền!');
            return redirect()->back()->withErrors('Bạn không có quyền!');
        }

        $user->name = $request->name;
        $user->email = $request->email;
        $user->status = $request->status;

        $supplier_arr = explode(' - ', $request->supplier);
        $supplier_code = $supplier_arr[0];
        $supplier_name   = $supplier_arr[1];
        $supplier = Supplier::where('code', $supplier_code)->where('name', $supplier_name)->first();

        $user->supplier_id = $supplier->id;
        $user->save();

        $request->session()->flash('message', 'Sửa xong người dùng!');
        return redirect()->route('admin.users.index');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(User $user)
    {
        //Check authorize
        if ('Quản trị' != Auth::user()->role->name) {
            Session::flash('message', 'Bạn không có quyền!');
            return redirect()->back()->withErrors('Bạn không có quyền!');
        }

        $user->delete();
        Session::flash('message', 'Xóa xong người dùng!');
        return redirect()->route('admin.users.index');
    }

    public function bulkDelete(Request $request)
    {
        //Check authorize
        if ('Quản trị' != Auth::user()->role->name) {
            $request->session()->flash('message', 'Bạn không có quyền!');
            return redirect()->back()->withErrors('Bạn không có quyền!');
        }

        $users = $request->users;
        foreach ($users as $user) {
            $deleted_user = User::findOrFail($user['id']);
            if ($deleted_user) {
                $deleted_user->destroy($deleted_user->id);
            }
        }

        $request->session()->flash('message', 'Xóa xong người dùng!');
        return redirect()->route('admin.users.index');
    }
}
