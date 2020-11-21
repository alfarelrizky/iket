<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Models\UserRequest;
use App\Models\Department;
use App\Models\Computer;
use App\Models\BreakType;

use App\Http\Requests\User\RequestRequest;

use PDF;
use DataTables;

class RequestController extends Controller
{
    public function json(){
        $data = UserRequest::with([
            'department', 'computer', 'break_type'
        ]);

        return DataTables::of($data)
        ->addColumn('action', function($data){
               $btn = '<a 
                href="request/print/'.$data->id.'" 
                class="btn btn-primary btn-sm mb-2" id="">
                <i class="fas fa-print"></i>&nbsp;&nbsp;Print
                </a>';

                return $btn;
        })
        ->make(true);
    }

    public function index() {
        // $items          = UserRequest::with([
        //     'break_type', 'computer', 'department'
        // ])->get();

        // $departments    = Department::all();
        // $computers      = Computer::all();
        // $break_types    = BreakType::all();

        // $items  = UserRequest::all();

        return view('pages.user.request-list', 
        // [
        //     'items'      => $items,
        // ]
        );
    }

    public function create() {
        $departments    = Department::all();
        $computers      = Computer::all();
        $break_types    = BreakType::all();

        return view('pages.user.request-create', [
            'departments'   => $departments,
            'computers'     => $computers,
            'break_types'   => $break_types
        ]);
    }

    public function store(RequestRequest $request) {
        // $validator = Validator::make($request->all(), [
        //     'request_created_date'  => 'required|date_format:Y-m-d|', 
        //     'client_name'           => 'required|max:100', 
        //     'department_id'         => 'required|integer|exists:departments,id', 
        //     'computer_id'           => 'required|integer|exists:computers,id', 
        //     'break_id'              => 'required|integer|exists:breaks,id', 
        //     'kind_of_repair'        => 'required|in:PERBAIKAN,FASILITAS',
        //     'description'           => 'max:255'
        // ]);
        
        // if ($validator->fails())
        //     return response()->json(['errors' => $validator->errors()->all()]);

        // $data = $request->all();
        
        // UserRequest::create($data);
        // return response()->json(['success' => 'Data berhasil ditambahkan']);


        $data = $request->all();

        UserRequest::create($data);
        return redirect()->route('user.request'); 
    }

    public function printPreview($id) {
        $item   = UserRequest::with([
            'break_type'
        ])->findOrFail($id);

        $pdf = PDF::loadView('pages.user.request-print', [
            'item'  =>  $item 
        ]);
        return $pdf->stream();
    }
}