<?php

namespace App\Repositories\Product;

use App\Models\Product\Category;
use Illuminate\Support\Facades\Auth;

class CategoryRepository
{
    public function query()
    {
        return Category::query();
    }
    public function getAll()
    {
        return Category::all();
    }

    public function getById($id)
    {
        return Category::find($id);
    }

    public function create(array $data)
    {
        return Category::create($data);
    }

    public function update($id, array $data)
    {
        return Category::where('id', $id)->update($data);
    }

    public function delete($id)
    {
//        Category::where('id', $id)->update(['deleted_by' => auth()->id()]);
        return Category::destroy($id);
    }

    public function getByParentIdWithRelationships($parent_id, $relations = []){
        $query = Category::where('parent_id', $parent_id);

        if (!empty($relations)) {
            $query->with($relations);
        }

        return $query->get();
    }

    public function getWithRelationships($relations = []){
        return Category::with($relations)->get();
    }

    public function getOrCreateCategoryId($categoryName)
    {
        if ($categoryName){
            $category =  Category::where('name', $categoryName)->first();
            if (!$category){
                $category = $this->create(['name' => $categoryName, 'created_by' => Auth::id()]);
            }
            return $category->id;
        }
    }

}
