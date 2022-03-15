<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\EmployeeStatus;
use Gate;


class EmployeeStatusController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
         if(Gate::denies('view')) {
               return abort('401');
         } 

         $employee_status = EmployeeStatus::orderBy('account_number');

         $employee_status = $employee_status->paginate((new EmployeeStatus())->perPage); 
         
         return view('employee_status.index',compact('employee_status'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        if(Gate::denies('add')) {
               return abort('401');
         } 

        return view('employee_status.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
          if(Gate::denies('add')) {
               return abort('401');
        } 

        $data = $request->except('_token');

        $request->validate([
              'name' => 'required|unique:employee_statuses',
              'account_number' => 'required|unique:employee_statuses',
        ]);

        $data['slug'] = \Str::slug($request->name);
            
        EmployeeStatus::create($data);

        return redirect('employee-status')->with('message', 'Employee Status Created Successfully!');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
          if(Gate::denies('edit')) {
               return abort('401');
          } 

         $type = EmployeeStatus::find($id);
         return view('employee_status.edit',compact('type'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        if(Gate::denies('update')) {
               return abort('401');
        } 

        $data = $request->except('_token');

        $request->validate([
              'name' => 'required|unique:employee_statuses,name,'.$id,
              'account_number' => 'required|unique:employee_statuses,account_number,'.$id,
        ]);

        $data['slug'] = \Str::slug($request->name);
         
         $employee_status = EmployeeStatus::find($id);

         if(!$employee_status){
            return redirect()->back();
         }
          
         $employee_status->update($data);

        return redirect('employee-status')->with('message', 'Employee Status Updated Successfully!');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
         if(Gate::denies('delete')) {
               return abort('401');
          } 

         $employee_status = EmployeeStatus::find($id);

         $employee_status->delete();       

        return redirect()->back()->with('message', 'Employee Status Delete Successfully!');
    }
}
