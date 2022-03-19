<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Company;
use App\Models\Document;
use Gate;


class CompanyController extends Controller
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

         $companies = Company::query();

         if(request()->filled('s')){
            $searchTerm = request()->s;
            $companies->where('first_name', 'LIKE', "%{$searchTerm}%") 
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

         $perPage = request()->filled('per_page') ? request()->per_page : (new Company())->perPage;

         $companies = $companies->paginate($perPage);

         return view('companies.index',compact('companies'));
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


        return view('companies.create');
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
              'name' => 'required|unique:companies,name'
        ]);

        $slug = \Str::slug($request->name);

        $data['photo'] = '';    

        if($request->hasFile('photo')){
               $photo = $request->file('photo');
               $photoName = $slug.'-'.time() . '.' . $photo->getClientOriginalExtension();
              
               $data['photo']  = $request->file('photo')->storeAs(Document::COMPANIES, $photoName, 'public');
        }

        $company = Company::create($data);

        return redirect('companies')->with('message', 'Company Created Successfully!');
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

         $company = Company::find($id);
         $companies = Company::all()->except($id);
         $codes = @$company->codes()->get();
              
         $perPage = request()->filled('per_page') ? request()->per_page : (new Company())->perPage;

         return view('companies.edit',compact('company','companies','codes'));
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
              'name' => 'required|unique:companies,name,'.$id
        ]);
     
        $slug = \Str::slug($request->name);
         
        $company = Company::find($id);
        $oldSlug = \Str::slug($company->name);

        if(!$company){
            return redirect()->back();
        }

        $data['photo'] = $company->photo;    

        if($request->hasFile('photo')){
               @unlink('storage/'.$company->photo);
               $photo = $request->file('photo');
               $photoName = $slug.'-'.time() . '.' . $photo->getClientOriginalExtension();
              
               $data['photo']  = $request->file('photo')->storeAs(Document::COMPANIES, $photoName, 'public');
        }
        
         $company->update($data);

        return redirect('companies')->with('message', 'Company Updated Successfully!');
    }


    public function createEmployees(Request $request, $id)
    {
        if(Gate::denies('add')) {
               return abort('401');
        } 

        $data = $request->except('_token');

        $company = Company::find($id);

        if(!$company){
            return redirect()->back();
        }
        
       $employees = Employee::all(); 

       return view('companies.includes.employee-create',compact('company','employees'));
    }

    public function editEmployees(Request $request, $id, $eid)
    {
        if(Gate::denies('edit')) {
               return abort('401');
        } 

        $data = $request->except('_token');

        $company = Company::find($id);

        if(!$company){
            return redirect()->back();
        }
        
       $employees = Employee::all(); 

       $employee = $company->employees()->whereEmployeeId($eid)->first();

       return view('companies.includes.employee-edit',compact('company','employees','employee'));
    }


     public function addEmployees(Request $request, $id)
    {
        if(Gate::denies('add')) {
               return abort('401');
        } 

        $data = $request->except('_token','employees');

        $company = Company::find($id);

        if(!$company){
            return redirect()->back();
        }   

        $company->employees()->syncWithPivotValues([$request->employees],$data); 

        return redirect(route('companies.show',['company' => $id]).'#employees')->with('message', 'Employee Add Successfully!');
    } 

     public function updateEmployees(Request $request, $id, $eid)
    {
        if(Gate::denies('update')) {
               return abort('401');
        } 

        $data = $request->except('_token','employees');

        $company = Company::find($id);

        if(!$company){
            return redirect()->back();
        }   
        

        $company->employees()->syncWithPivotValues([$request->employees],$data); 

        return redirect(route('companies.show',['company' => $id]).'#employees')->with('message', 'Employee Updated Successfully!');
    }

    public function deleteEmployee (Request $request, $id, $eid)
    {
        if(Gate::denies('delete')) {
               return abort('401');
        } 

        $company = Company::find($id);

        if(!$company){
            return redirect()->back();
        }
        
        $company->employees()->detach([$eid]); 

        return redirect(route('companies.show',['company' => $id]).'#employees')->with('message', 'Employee delete Successfully!');
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

         $company = Company::find($id);
         $company_slug = \Str::slug($company->name);
         $company_type = @$company->company_type;

         $company_type_slug = @$company_type->slug;

         $public_path = public_path().'/';

         $folderPath = Document::COMPANY."/";

         $company_type_slug = ($company_type_slug) ? $company_type_slug : Document::ARCHIEVED; 

         $folderPath .= "$company_type_slug/$company_slug";

         $path = $public_path.'/'.$folderPath;


         $aPath = public_path().'/'.Document::COMPANY.'/'.Document::ARCHIEVED.'/'.Document::COMPANIES; 
         
         @\File::makeDirectory($aPath, $mode = 0777, true, true);

         @\File::copyDirectory($path, $aPath.'/'.$project_slug);

         @\File::deleteDirectory($path);

         @unlink('storage/'.$company->photo);

         $company->delete();

        return redirect()->back()->with('message', 'Company Delete Successfully!');
    }
}
