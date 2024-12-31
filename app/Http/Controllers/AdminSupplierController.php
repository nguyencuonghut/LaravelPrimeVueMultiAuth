<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreSupplierRequest;
use App\Http\Requests\UpdateSupplierRequest;
use App\Models\Supplier;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use Inertia\Inertia;

class AdminSupplierController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $suppliers = Supplier::orderBy('id', 'desc')->get()->map(function ($supplier) {
            $users_str = '';
            $i = 0;
            $length = count($supplier->users);
            if ($length) {
                foreach ($supplier->users as $user) {
                    $users_str .= $user->email;
                    if(++$i !== $length) {
                        $users_str .= ', ';
                    }
                }
            }
            return [
                'id' => $supplier->id,
                'code' => $supplier->code,
                'name' => $supplier->name,
                'users' => $users_str,
                'status' => $supplier->status,
            ];
        });

        $can = [
            'create_supplier' => 'Quản trị' ==  Auth::user()->role->name || 'Nhân viên mua hàng' == Auth::user()->role->name,
            'update_supplier' => 'Quản trị' ==  Auth::user()->role->name || 'Nhân viên mua hàng' == Auth::user()->role->name,
            'delete_supplier' => 'Quản trị' ==  Auth::user()->role->name || 'Nhân viên mua hàng' == Auth::user()->role->name,
            'import_supplier' => 'Quản trị' ==  Auth::user()->role->name || 'Nhân viên mua hàng' == Auth::user()->role->name,
            'export_supplier' => 'Quản trị' ==  Auth::user()->role->name || 'Nhân viên mua hàng' == Auth::user()->role->name,
        ];

        return Inertia::render('SupplierIndex', [
            'suppliers' => $suppliers,
            'can' => $can,
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreSupplierRequest $request)
    {
        //Check authorize
        if ('Quản trị' != Auth::user()->role->name) {
            $request->session()->flash('message', 'Bạn không có quyền!');
            return redirect()->back()->withErrors('Bạn không có quyền!');
        }

        $supplier = new Supplier();

        $supplier->code = $request->code;
        $supplier->name = $request->name;
        $supplier->status = $request->status;
        $supplier->save();

        $request->session()->flash('message', 'Tạo xong nhà cung cấp!');
        return redirect()->route('admin.suppliers.index');
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
    public function update(UpdateSupplierRequest $request, Supplier $supplier)
    {
        //Check authorize
        if ('Quản trị' != Auth::user()->role->name) {
            $request->session()->flash('message', 'Bạn không có quyền!');
            return redirect()->back()->withErrors('Bạn không có quyền!');
        }

        $supplier->name = $request->name;
        $supplier->name = $request->name;
        $supplier->status = $request->status;
        $supplier->save();

        $request->session()->flash('message', 'Sửa xong nhà cung cấp!');
        return redirect()->route('admin.suppliers.index');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Supplier $supplier)
    {
        //Check authorize
        if ('Quản trị' != Auth::user()->role->name) {
            Session::flash('message', 'Bạn không có quyền!');
            return redirect()->back()->withErrors('Bạn không có quyền!');
        }

        $supplier->delete();
        Session::flash('message', 'Xóa xong nhà cung cấp!');
        return redirect()->route('admin.suppliers.index');
    }

    public function bulkDelete(Request $request)
    {
        //Check authorize
        if ('Quản trị' != Auth::user()->role->name) {
            $request->session()->flash('message', 'Bạn không có quyền!');
            return redirect()->back()->withErrors('Bạn không có quyền!');
        }

        $suppliers = $request->suppliers;
        foreach ($suppliers as $supplier) {
            $deleted_supplier = Supplier::findOrFail($supplier['id']);
            if ($deleted_supplier) {
                $deleted_supplier->destroy($deleted_supplier->id);
            }
        }

        $request->session()->flash('message', 'Xóa xong nhà cung cấp!');
        return redirect()->route('admin.suppliers.index');
    }
}
