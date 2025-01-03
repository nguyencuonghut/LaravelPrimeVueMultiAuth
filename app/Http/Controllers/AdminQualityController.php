<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreQualityRequest;
use App\Http\Requests\UpdateQualityRequest;
use App\Models\Material;
use App\Models\Quality;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use Inertia\Inertia;

class AdminQualityController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $qualities = Quality::orderBy('material_id', 'asc')->get()->map(function ($quality) {
            return [
                'id' => $quality->id,
                'material' => $quality->material->code  . ' - ' . $quality->material->name,
                'detail' => $quality->detail,
                'status' => $quality->status,
            ];
        });

        $can = [
            'create_quality' => 'Quản trị' ==  Auth::user()->role->name || 'Nhân viên kiểm soát' == Auth::user()->role->name,
            'update_quality' => 'Quản trị' ==  Auth::user()->role->name || 'Nhân viên kiểm soát' == Auth::user()->role->name,
            'delete_quality' => 'Quản trị' ==  Auth::user()->role->name || 'Nhân viên kiểm soát' == Auth::user()->role->name,
            'import_quality' => 'Quản trị' ==  Auth::user()->role->name || 'Nhân viên kiểm soát' == Auth::user()->role->name,
            'export_quality' => 'Quản trị' ==  Auth::user()->role->name || 'Nhân viên kiểm soát' == Auth::user()->role->name,
        ];

        $materials = Material::orderBy('code', 'asc')->get()->map(function ($material) {
            return $material->code . ' - ' . $material->name;
        });

        return Inertia::render('QualityIndex', [
            'qualities' => $qualities,
            'can' => $can,
            'materials' => $materials,
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreQualityRequest $request)
    {
        //Check authorize
        if ('Quản trị' != Auth::user()->role->name) {
            $request->session()->flash('message', 'Bạn không có quyền!');
            return redirect()->back()->withErrors('Bạn không có quyền!');
        }

        $material_arr = explode(' - ', $request->material);
        $material_code = $material_arr[0];
        $material_name   = $material_arr[1];
        $material = Material::where('code', $material_code)->where('name', $material_name)->first();
        $on_qualities = Quality::where('material_id', $material->id)->where('status', 'On')->get();
        //Check if Material with On status existed
        if ($on_qualities->count()
            && 'On' == $request->status) {
            $request->session()->flash('message', 'Mỗi nguyên liệu chỉ cho phép 1 tiêu chuẩn ở trạng thái On!');
            return redirect()->back()->withErrors('Mỗi nguyên liệu chỉ cho phép 1 tiêu chuẩn ở trạng thái On!');
        } else {
            $quality = new Quality();
            $quality->material_id = $material->id;
            $quality->detail = $request->detail;
            $quality->status = $request->status;
            $quality->save();
        }

        $request->session()->flash('message', 'Tạo xong chất lượng!');
        return redirect()->route('admin.qualities.index');
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
    public function update(UpdateQualityRequest $request, Quality $quality)
    {
        //Check authorize
        if ('Quản trị' != Auth::user()->role->name) {
            $request->session()->flash('message', 'Bạn không có quyền!');
            return redirect()->back()->withErrors('Bạn không có quyền!');
        }

        $material_arr = explode(' - ', $request->material);
        $material_code = $material_arr[0];
        $material_name   = $material_arr[1];
        $material = Material::where('code', $material_code)->where('name', $material_name)->first();
        $on_qualities = Quality::where('material_id', $material->id)->where('status', 'On')->get();
        //1 Material has only 1 Quality which status is On
        if ($on_qualities->count()
            && 'On' == $request->status) {
            $request->session()->flash('message', 'Mỗi nguyên liệu chỉ cho phép 1 tiêu chuẩn ở trạng thái On!');
            return redirect()->back()->withErrors('Mỗi nguyên liệu chỉ cho phép 1 tiêu chuẩn ở trạng thái On!');
        } else {
            $quality->material_id = $material->id;
            $quality->detail = $request->detail;
            $quality->status = $request->status;
            $quality->save();
        }

        $request->session()->flash('message', 'Sửa xong chất lượng!');
        return redirect()->route('admin.qualities.index');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Quality $quality)
    {
        //Check authorize
        if ('Quản trị' != Auth::user()->role->name) {
            Session::flash('message', 'Bạn không có quyền!');
            return redirect()->back()->withErrors('Bạn không có quyền!');
        }

        $quality->delete();
        Session::flash('message', 'Xóa xong chất lượng!');
        return redirect()->route('admin.qualities.index');
    }

    public function bulkDelete(Request $request)
    {
        //Check authorize
        if ('Quản trị' != Auth::user()->role->name) {
            $request->session()->flash('message', 'Bạn không có quyền!');
            return redirect()->back()->withErrors('Bạn không có quyền!');
        }

        $qualities = $request->qualities;
        foreach ($qualities as $quality) {
            $deleted_quality = Quality::findOrFail($quality['id']);
            if ($deleted_quality) {
                $deleted_quality->destroy($deleted_quality->id);
            }
        }

        $request->session()->flash('message', 'Xóa xong chất lượng!');
        return redirect()->route('admin.qualities.index');
    }
}
