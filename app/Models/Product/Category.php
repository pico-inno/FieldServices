<?php

namespace App\Models\Product;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Category extends Model
{
    use HasFactory;
	use SoftDeletes;

	protected $fillable = [
		'name',
		'short_code',
		'parent_id',
		'description',
		'created_by'
	];

    public function parentCategory()
	{
		return $this->belongsTo(Category::class, 'parent_id');
	}
	
	public function childCategory()
	{
		return $this->hasMany(Category::class, 'parent_id');
	}
}
