<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreMaterialRequest;
use App\Http\Requests\UpdateMaterialRequest;
use App\Models\Material;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use Inertia\Inertia;

class AdminMaterialController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $materials = Material::orderBy('id', 'desc')->get()->map(function ($material) {
            return [
                'id' => $material->id,
                'code' => $material->code,
                'name' => $material->name,
                'status' => $material->status,
            ];
        });

        $can = [
            'create_material' => 'Quản trị' ==  Auth::user()->role->name || 'Nhân viên kiểm soát' == Auth::user()->role->name,
            'update_material' => 'Quản trị' ==  Auth::user()->role->name || 'Nhân viên kiểm soát' == Auth::user()->role->name,
            'delete_material' => 'Quản trị' ==  Auth::user()->role->name || 'Nhân viên kiểm soát' == Auth::user()->role->name,
            'import_material' => 'Quản trị' ==  Auth::user()->role->name || 'Nhân viên kiểm soát' == Auth::user()->role->name,
            'export_material' => 'Quản trị' ==  Auth::user()->role->name || 'Nhân viên kiểm soát' == Auth::user()->role->name,
        ];

        return Inertia::render('MaterialIndex', [
            'materials' => $materials,
            'can' => $can,
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreMaterialRequest $request)
    {
        //Check authorize
        if ('Quản trị' != Auth::user()->role->name) {
            $request->session()->flash('message', 'Bạn không có quyền!');
            return redirect()->back()->withErrors('Bạn không có quyền!');
        }

        $material = new Material();

        $material->code = $request->code;
        $material->name = $request->name;
        $material->status = $request->status;
        $material->save();

        $request->session()->flash('message', 'Tạo xong hàng hóa!');
        return redirect()->route('admin.materials.index');
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
    public function update(UpdateMaterialRequest $request, Material $material)
    {
        //Check authorize
        if ('Quản trị' != Auth::user()->role->name) {
            $request->session()->flash('message', 'Bạn không có quyền!');
            return redirect()->back()->withErrors('Bạn không có quyền!');
        }

        $material->code = $request->code;
        $material->name = $request->name;
        $material->status = $request->status;
        $material->save();

        $request->session()->flash('message', 'Sửa xong nhà cung cấp!');
        return redirect()->route('admin.materials.index');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Material $material)
    {
        //Check authorize
        if ('Quản trị' != Auth::user()->role->name) {
            Session::flash('message', 'Bạn không có quyền!');
            return redirect()->back()->withErrors('Bạn không có quyền!');
        }

        $material->delete();
        Session::flash('message', 'Xóa xong nhà cung cấp!');
        return redirect()->route('admin.materials.index');
    }

    public function bulkDelete(Request $request)
    {
        //Check authorize
        if ('Quản trị' != Auth::user()->role->name) {
            $request->session()->flash('message', 'Bạn không có quyền!');
            return redirect()->back()->withErrors('Bạn không có quyền!');
        }

        $materials = $request->materials;
        foreach ($materials as $material) {
            $deleted_material = Material::findOrFail($material['id']);
            if ($deleted_material) {
                $deleted_material->destroy($deleted_material->id);
            }
        }

        $request->session()->flash('message', 'Xóa xong nhà cung cấp!');
        return redirect()->route('admin.materials.index');
    }
}
