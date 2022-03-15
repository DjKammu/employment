<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class HolidayList extends Model
{
    use HasFactory;

    protected $perPage = 9;

    protected $fillable = [
     'name' , 'company_id' ,
     'year','holiday_date' 
    ];
    protected $dates = ['holiday_date'];
   
   public function startYear(){

   	return Carbon::now()->year;

   }

   public function endYear(){

   	return $this->startYear() + 10;

   }

}
