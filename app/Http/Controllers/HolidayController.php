<?php
namespace App\Http\Controllers;

use Illuminate\Validation\Rule;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;
use App\Models\HolidayList;
use App\Models\LeaveRule;
use App\Models\LeaveType;
use App\Models\Company;
use App\Models\Document;
use App\Models\Holiday;
use Gate;
use Carbon\Carbon;

class HolidayController extends Controller
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
         //
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(Request $request, $id, $year)
    {
        if(Gate::denies('add')) {
               return abort('401');
         } 

        return view('companies.includes.holidays-create');
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
              'name'         => 'required',
              'company_id'   => 'required|exists:companies,id',
              'year'         => 'required',
              'holiday_date' => 'required' 
        ]);

        HolidayList::create($data);

        return redirect(route('companies.show',['company' => $request->company_id]).'#holidays')->with('message', 'Holiday Created Successfully!');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(Request $request, $id)
    {
          if(Gate::denies('edit')) {
               return abort('401');
          } 

         $holidayList = HolidayList::find($id);         

         return view('companies.includes.holidays-edit',compact('holidayList'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function addWeekend(Request $request, $id)
    {
         if(Gate::denies('add')) {
               return abort('401');
        } 

        $data = $request->except('_token');
        $data['company_id'] = $id;

        $data['year'] = ($data['year']) ?? (new Holiday)->startYear();
   
        Holiday::updateOrCreate(
            ['company_id' => $id, 'year' => $year],
            $data
           );;

        return redirect(route('companies.show',['company' => $id]).'#holidays')->with('message', 'Weekend Saved Successfully!');
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function storeMultiple(Request $request, $id, $year)
    {
         if(Gate::denies('add')) {
               return abort('401');
        } 

        $data = $request->except('_token');

        $request->validate([
              'company_id'   => 'required|exists:companies,id'
        ]);

        $holiday = Holiday::whereCompanyId($data['company_id'])
                           ->where('year',$year)->first();

        $holidayList = HolidayList::whereCompanyId($data['company_id'])
                           ->where('year',$year)->get();
       
       if($holidayList->count() == 0  && !$holiday){
             return redirect(route('companies.show',['company' => $id]).'#holidays')->withErrors('Not exists any Holiday for this company');
       }
       
       if($holiday){
          $holiday = $holiday->toArray();
          $holiday =  Arr::except($holiday, ['company_id','id']);
          Holiday::updateOrCreate(
              ['company_id' => $id, 'year' => $year],
             $holiday
          );
       }

       if($holidayList->count() > 0){
          foreach ($holidayList as $key => $list) {
            $list = $list->toArray();
            $list =  Arr::except($list, ['company_id','id']);
    
             HolidayList::updateOrCreate(
                ['company_id' => $id, 'year' => $year, 'name' => $list['name']],
                $list
            );
          }
          
       }
       
        return redirect(route('companies.show',['company' => $id]).'#holidays')->with('message', 'Weekend and Holiday Successfully!');
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
              'name'         => 'required',
              'holiday_date' => 'required' 
        ]);
 

         $holidayList = HolidayList::find($id);
         
         if(!$holidayList){
            return redirect()->back();
         }
          
         
         $holidayList->update($data);

        return redirect(route('companies.show',['company' => $holidayList->company_id ]).'#holidays')->with('message', 'Holiday Updated Successfully!');
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

         $HolidayList = HolidayList::find($id);

         $HolidayList->delete();       

        return redirect()->back()->with('message', 'Holiday Delete Successfully!');
    }


}
