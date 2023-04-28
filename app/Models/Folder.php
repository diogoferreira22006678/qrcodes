<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Folder extends Model
{
    use HasFactory;

    protected $table = 'folders';
    protected $primaryKey = 'folder_id';
    protected $fillable = ['folder_name', 'folder_path', 'category_id', 'folder_description'];

    public function docs()
    {
        return $this->hasMany(Doc::class, 'folder_id', 'folder_id');
    }

    public function categories()
    {
        return $this->hasOne(Category::class, 'category_id', 'category_id');
    }
}
