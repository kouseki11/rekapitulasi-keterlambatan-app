<?php

namespace App\Http\Controllers;

use App\Models\Student;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;
use App\Models\Late;
use Throwable;

class LateController extends Controller
{
    public function index()
    {
        $data['title'] = 'Daftar Late';
        $data['late'] = Late::all();
        $data['page'] = 'late';
        return view('pages.late.index', $data);
    }

    public function create()
    {
        $data['title'] = 'Tambah Late';
        $data['page'] = 'late';
        $data['students'] = Student::all();
        return view('pages.late.create', $data);
    }

    public function store(Request $request)
    {
        try {
            DB::beginTransaction();
            $validator = Validator::make($request->all(),[
                'student_id' => 'required',
                'date_time_late'=>'required',
                'information'=>'required',
                'bukti'=>'required',
            ]);

            if($validator->fails()){
                return redirect()->back()->withErrors($validator->errors())->withInput($request->all());
            }
            $data = $validator->validated();

            Late::create($data);
            DB::commit();
            return redirect()->route('late.index')->with('success', 'Data Berhasil Ditambahkan');
        } catch (Throwable $e) {
            DB::rollback();
            Log::debug('LateController store() ' . $e->getMessage());
            return redirect()->back()->with('error', $e->getMessage());
        }
    }

    public function show($id)
    {
        $data['title'] = 'Detail Late';
        $data['late'] = Late::find($id);
        $data['page'] = 'late';
        return view('pages.late.show', $data);
    }

    public function edit($id)
    {
        $data['title'] = 'Edit Late';
        $data['late'] = Late::find($id);
        $data['page'] = 'late';
        return view('pages.late.edit', $data);
    }

    public function update(Request $request, $id)
    {
        try {
            DB::beginTransaction();
            $validator = Validator::make($request->all(),[
                'student_id' => 'required',
                'date_time_late'=>'required',
                'information'=>'required',
                'bukti'=>'required',
            ]);

            if($validator->fails()){
                return redirect()->back()->withErrors($validator->errors())->withInput($request->all());
            }
            $data = $validator->validated();

            Late::find($id)->update($data);
            DB::commit();
            return redirect()->route('late.index')->with('success', 'Data Berhasil Diedit');
        } catch (Throwable $e) {
            DB::rollback();
            Log::debug('LateController update() ' . $e->getMessage());
            return redirect()->back()->with('error', $e->getMessage());
        }
    }

    public function destroy($id)
    {
        try {
            DB::beginTransaction();
            Late::find($id)->delete();
            DB::commit();
            return redirect()->route('late.index')->with('success', 'Data Berhasil Dihapus');
        } catch (Throwable $e) {
            DB::rollback();
            Log::debug('LateController destroy() ' . $e->getMessage());
            return redirect()->back()->with('error', $e->getMessage());
        }
    }
}