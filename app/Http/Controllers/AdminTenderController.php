<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreTenderRequest;
use App\Models\Admin;
use App\Models\Tender;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Session;
use Inertia\Inertia;

class AdminTenderController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $tenders = Tender::orderBy('id', 'desc')
                        ->with(['creator', 'reviewer', 'auditor', 'approver'])
                        ->get()
                        ->map(function ($tender) {
            return [
                'id' => $tender->id,
                'code' => $tender->code,
                'title' => $tender->title,
                'packing' => $tender->packing,
                'origin' => $tender->origin,
                'delivery_condition' => $tender->delivery_condition,
                'payment_condition' => $tender->payment_condition,
                'freight_charge' => $tender->freight_charge,
                'certificate' => $tender->certificate,
                'other_term' => $tender->other_term,
                'start_time' => date('d/m/Y H:i', strtotime($tender->start_time)),
                'end_time' => date('d/m/Y H:i', strtotime($tender->end_time)),
                'creator' => $tender->creator->name,
                'status' => $tender->status,
            ];
        });

        $can = [
            'create_tender' => 'Nhân viên mua hàng' ==  Auth::user()->role->name || 'Quản trị' ==  Auth::user()->role->name,
            'update_tender' => 'Nhân viên mua hàng' ==  Auth::user()->role->name || 'Quản trị' ==  Auth::user()->role->name,
            'delete_tender' => 'Nhân viên mua hàng' ==  Auth::user()->role->name || 'Quản trị' ==  Auth::user()->role->name,
            'export_tender' => 'Nhân viên mua hàng' ==  Auth::user()->role->name || 'Quản trị' ==  Auth::user()->role->name,
        ];

        $admins = Admin::where('status', 'On')->orderBy('id', 'desc')->get()->map(function ($admin) {
            return collect($admin)->only(['name']);
        });

        return Inertia::render('TenderIndex', [
            'tenders' => $tenders,
            'can' => $can,
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $can = [
            'create_tender' => 'Nhân viên mua hàng' ==  Auth::user()->role->name || 'Quản trị' ==  Auth::user()->role->name,
            'update_tender' => 'Nhân viên mua hàng' ==  Auth::user()->role->name || 'Quản trị' ==  Auth::user()->role->name,
            'delete_tender' => 'Nhân viên mua hàng' ==  Auth::user()->role->name || 'Quản trị' ==  Auth::user()->role->name,
            'export_tender' => 'Nhân viên mua hàng' ==  Auth::user()->role->name || 'Quản trị' ==  Auth::user()->role->name,
        ];
        return Inertia::render('TenderCreate', [
            'can' => $can,
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreTenderRequest $request)
    {
        //Check authorize
        if ('Quản trị' != Auth::user()->role->name
            || 'Nhân viên mua hàng' == Auth::user()->role->name) {
            $request->session()->flash('message', 'Bạn không có quyền!');
            return redirect()->back()->withErrors('Bạn không có quyền!');
        }

        $tender = new Tender();
        $tender->code = '2025/01/06/' . rand(1, 1000);
        $tender->title = $request->title;
        $tender->packing = $request->packing;
        $tender->origin = $request->origin;
        $tender->delivery_condition = $request->delivery_condition;
        $tender->payment_condition = $request->payment_condition;
        $tender->freight_charge = $request->freight_charge;
        $tender->certificate = $request->certificate;
        $tender->other_term = $request->other_term;
        $tender->start_time = Carbon::parse($request->start_time)->tz('Asia/Ho_Chi_Minh');
        $tender->end_time = Carbon::parse($request->end_time)->tz('Asia/Ho_Chi_Minh');
        $tender->creator_id = Auth::user()->id;
        $tender->status = 'Mở';
        $tender->save();

        $request->session()->flash('message', 'Tạo xong bộ thầu!');
        return redirect()->route('admin.tenders.index');
    }

    /**
     * Display the specified resource.
     */
    public function show(Tender $tender)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Tender $tender)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Tender $tender)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Tender $tender)
    {
        //Check authorize
        if ('Quản trị' != Auth::user()->role->name
        || 'Nhân viên mua hàng' == Auth::user()->role->name) {
            Session::flash('message', 'Bạn không có quyền!');
            return redirect()->back()->withErrors('Bạn không có quyền!');
        }

        $tender->delete();
        Session::flash('message', 'Xóa xong bộ thầu!');
        return redirect()->route('admin.tenders.index');
    }
}
