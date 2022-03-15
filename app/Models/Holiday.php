<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class Holiday extends Model
{
    use HasFactory;

    protected $perPage = 9;

    protected $fillable = [
     'company_id' , 'year' ,
     'saturday_off',
     'sunday_off' 
    ];
   
   public function startYear(){

   	return Carbon::now()->year;

   }

   public function endYear(){

   	return $this->startYear() + 10;

   }


}
