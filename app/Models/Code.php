<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Code extends Model
{
    use HasFactory;
    
    protected $perPage = 10;

    protected $fillable = [
     'company_id', 'code',
     'company_nick_name',
     'form_link','title',
     'template_id','client_id',
     'client_secret'
    ];


    public function company(){

        return $this->belongsTo(Company::class);
    }
     

    static public function validURL($url) {

      if (substr($url, 0, 7) == 'http://') { return $url; }
      if (substr($url, 0, 8) == 'https://') { return $url; }
      return 'http://'. $url;

    }
}
