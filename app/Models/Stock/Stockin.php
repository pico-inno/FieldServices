<?php
//
//namespace App\Models\Stock;
//
//use App\Models\BusinessUser;
//use App\Models\Product\Brand;
//use App\Models\Product\Category;
//use App\Models\Product\Product;
//use App\Models\Product\VariationTemplateValues;
//use Illuminate\Database\Eloquent\Model;
//use App\Models\settings\businessLocation;
//use Illuminate\Database\Eloquent\Relations\BelongsTo;
//use Illuminate\Database\Eloquent\Factories\HasFactory;
//use Illuminate\Database\Eloquent\Relations\HasOne;
//use Illuminate\Database\Eloquent\SoftDeletes;
//
//class Stockin extends Model
//{
//    use HasFactory;
//    use SoftDeletes;
//    public $timestamps = true;
//
//    protected $fillable = [
//        'business_location_id',
//        'stockin_voucher_no',
//        'stockin_date',
//        'stockin_person',
//        'note',
//        'created_by',
//    ];
//
//    public function product(){
//        return $this->belongsTo(Product::class, 'product_id', 'id');
//    }
//    public function businessLocation(): BelongsTo
//    {
//        return $this->belongsTo(businessLocation::class, 'business_location_id', 'id');
//    }
//    public function stockinPerson()
//    {
//        return $this->belongsTo(BusinessUser::class, 'stockin_person');
//    }
//
//    public function stockindetails()
//    {
//        return $this->hasMany(StockinDetail::class);
//    }
//
//    public function created_by(): HasOne
//    {
//        return $this->hasOne(BusinessUser::class, 'id', 'created_by');
//    }
//
//    public function confirm_by(): HasOne
//    {
//        return $this->hasOne(BusinessUser::class, 'id', 'confirm_by');
//    }
//
//    public function variationTempalteValues(): HasOne
//    {
//        return $this->hasOne(VariationTemplateValues::class,'id','variation_id');
//    }
//
//    public function category(): BelongsTo
//    {
//        return $this->belongsTo(Category::class, 'id', 'category_id');
//    }
//
//    public function brand(): BelongsTo
//    {
//        return $this->belongsTo(Brand::class, 'id', 'brand_id');
//    }
//}
