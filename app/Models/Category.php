<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    // កំណត់ជួរឈរដែលអនុញ្ញាតឱ្យបញ្ចូលទិន្នន័យបាន
    protected $fillable = ['name_kh', 'name_en', 'description'];
}
