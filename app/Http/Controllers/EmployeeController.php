<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\DocumentType;
use App\Models\Document;
use App\Models\EmployeeStatus;
use App\Models\Employee;
use App\Models\Subcontractor;
use App\Models\Proposal;
use App\Models\Category;
use App\Models\Vendor;
use Gate;


class EmployeeController extends Controller
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

         $employees = Employee::query();

         if(request()->filled('s')){
            $searchTerm = request()->s;
            $employees->where('first_name', 'LIKE', "%{$searchTerm}%") 
            ->orWhere('social_society_number', 'LIKE', "%{$searchTerm}%")
            ->orWhere('phone_number_1', 'LIKE', "%{$searchTerm}%")
            ->orWhere('phone_number_2', 'LIKE', "%{$searchTerm}%")
            ->orWhere('eamil_address', 'LIKE', "%{$searchTerm}%")
            ->orWhere('middle_name', 'LIKE', "%{$searchTerm}%")
            ->orWhere('last_name', 'LIKE', "%{$searchTerm}%")
            ->orWhere('address_1', 'LIKE', "%{$searchTerm}%")
            ->orWhere('address_2', 'LIKE', "%{$searchTerm}%")
            ->orWhere('zip_code', 'LIKE', "%{$searchTerm}%")
            ->orWhere('country', 'LIKE', "%{$searchTerm}%")
            ->orWhere('city', 'LIKE', "%{$searchTerm}%")
            ->orWhere('state', 'LIKE', "%{$searchTerm}%")
            ->orWhere('notes', 'LIKE', "%{$searchTerm}%");
         }  

         if(request()->filled('p')){
            $p = request()->p;
            $employees->whereHas('employee_status', function($q) use ($p){
                $q->where('slug', $p);
            });
         } 
         
         $employeeStatus = EmployeeStatus::all(); 

         $perPage = request()->filled('per_page') ? request()->per_page : (new Employee())->perPage;

         $employees = $employees->paginate($perPage);

         return view('employees.index',compact('employees','employeeStatus'));
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

        $employeeStatus = EmployeeStatus::all(); 

        return view('employees.create',compact('employeeStatus'));
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
              'first_name' => 'required',
              'employee_status_id' => 'required|exists:employee_statuses,id',
              'email_address' => 'required|unique:employees',
              'dob'    => 'nullable|date',
              'doh'    => 'nullable|date',
              'td'     => 'nullable|date|after_or_equal:doh'
        ]);

        $slug = \Str::slug($request->first_name);

        $data['photo'] = '';    

        if($request->hasFile('photo')){
               $photo = $request->file('photo');
               $photoName = $slug.'-'.time() . '.' . $photo->getClientOriginalExtension();
              
               $data['photo']  = $request->file('photo')->storeAs(Document::EMPLOYEES, $photoName, 'public');
        }

        $property = Employee::create($data);

        return redirect('employees')->with('message', 'Employee Created Successfully!');
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

         $employee = Employee::find($id);
         $employeeStatus = EmployeeStatus::all();
                  
         return view('employees.edit',compact('employee','employeeStatus'));
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
              'first_name' => 'required',
              'employee_status_id' => 'required|exists:employee_statuses,id',
              'email_address' => 'required|unique:employees,email_address,'.$id,
              'dob'    => 'nullable|date',
              'doh'    => 'nullable|date',
              'td'     => 'nullable|date|after_or_equal:doh'
        ]);
     
         $slug = \Str::slug($request->first_name);
         
        $employee = Employee::find($id);

        if(!$employee){
            return redirect()->back();
        }

        $data['photo'] = $employee->photo;    

        if($request->hasFile('photo')){
               @unlink('storage/'.$employee->photo);
               $photo = $request->file('photo');
               $photoName = $slug.'-'.time() . '.' . $photo->getClientOriginalExtension();
              
               $data['photo']  = $request->file('photo')->storeAs(Document::EMPLOYEES, $photoName, 'public');
        }
        

         $employee->update($data);

        return redirect('employees')->with('message', 'Employee Updated Successfully!');
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

         $employee = Employee::find($id);

         @unlink('storage/'.$employee->photo);
          
         $employee->delete();

        return redirect()->back()->with('message', 'Employee Delete Successfully!');
    }
}
