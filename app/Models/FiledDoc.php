<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FiledDoc extends Model
{
    use HasFactory;

    protected $table = 'files';

    protected $fillable = [
        'filename',
        'description',
        'date_filed',
        'year',
        'semester',
        'deleted',
        'status',
        'org_id',
        'user_id',
    ];

    public function files(){
        return $this->hasMany(Upload::class, 'file_id', 'id')->orderBy('created_at', 'asc');
    }
}
