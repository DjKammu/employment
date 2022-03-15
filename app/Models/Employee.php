<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Employee extends Model
{
    use HasFactory;

    protected $perPage = 9;

    protected $fillable = [
     'first_name' , 'middle_name' ,'last_name',
     'address_1' , 'address_2' ,'phone_number_1' ,
      'phone_number_2' ,'city','state' , 'country',
      'zip_code','notes' ,'photo','eamil_address',
     'employee_status_id' ,'social_society_number',
     'dob','doh','td','email_address'
    ];

    public function employee_status(){
    	return $this->belongsTo(EmployeeStatus::class);
    }

    public function leaves(){
    	return $this->hasMany(Leave::class);
    }

    
}
