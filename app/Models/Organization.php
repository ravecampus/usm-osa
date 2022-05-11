<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Organization extends Model
{
    use HasFactory;

    protected $table = 'organizations';

    protected $fillable = [
        'name',
        'description',
        'abbreviation',
        'registration_number',
        'organization_first_registered',
        'adviser',
        'status',
        'deleted',
        'user_id',
        'category_id',
        'year',
        'semester'
    ];

    public function docs(){
        return $this->hasMany(FiledDoc::class, 'org_id', 'id')->orderBy('created_at', 'asc');
    }

    public function category(){
        return $this->hasOne(Category::class, 'id', 'category_id');
    }




}
