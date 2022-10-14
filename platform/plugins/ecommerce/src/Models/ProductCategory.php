<?php

namespace Botble\Ecommerce\Models;

use Botble\Base\Enums\BaseStatusEnum;
use Botble\Base\Models\BaseModel;
use Botble\Base\Traits\EnumCastable;
use Botble\Ecommerce\Tables\ProductTable;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Html;
use Illuminate\Support\Collection;
use Illuminate\Support\Str;

class ProductCategory extends BaseModel
{
    use EnumCastable;

    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'ec_product_categories';

    /**
     * @var array
     */
    protected $fillable = [
        'name',
        'parent_id',
        'description',
        'order',
        'status',
        'image',
        'is_featured',
    ];

    /**
     * @var array
     */
    protected $casts = [
        'status' => BaseStatusEnum::class,
    ];

    /**
     * @return BelongsToMany
     */
    public function products(): BelongsToMany
    {
        return $this
            ->belongsToMany(Product::class, 'ec_product_category_product', 'category_id', 'product_id')
            ->where('is_variation', 0);
    }

    /**
     * @return BelongsTo
     */
    public function parent(): BelongsTo
    {
        return $this->belongsTo(ProductCategory::class, 'parent_id')->withDefault();
    }

    /**
     * @return Collection
     */
    public function getParentsAttribute(): Collection
    {
        $parents = collect([]);

        $parent = $this->parent;

        while ($parent->id) {
            $parents->push($parent);
            $parent = $parent->parent;
        }

        return $parents;
    }

    /**
     * @return HasMany
     */
    public function children(): HasMany
    {
        return $this->hasMany(ProductCategory::class, 'parent_id');
    }

    /**
     * @return HasMany
     */
    public function activeChildren(): HasMany
    {
        return $this->children()->where('status', BaseStatusEnum::PUBLISHED);
    }

    /**
     * @param $category
     * @param array $childrenIds
     * @return array
     */
    public function getChildrenIds($category, array $childrenIds = []): array
    {
        $children = $category->children()->select('id')->get();

        foreach ($children as $child) {
            $childrenIds[] = $child->id;

            $childrenIds = array_merge($childrenIds, $this->getChildrenIds($child, $childrenIds));
        }

        return array_unique($childrenIds);
    }

    /**
     * @return \Illuminate\Support\HtmlString|string
     */
    public function getBadgeWithCountAttribute()
    {
        switch ($this->status->getValue()) {
            case BaseStatusEnum::DRAFT:
                $badge = 'bg-secondary';
                break;

            case BaseStatusEnum::PENDING:
                $badge = 'bg-warning';
                break;

            default:
                $badge = 'bg-success';
                break;
        }

        $link = route('products.index', [
            'filter_table_id'  => strtolower(Str::slug(Str::snake(ProductTable::class))),
            'class'            => Product::class,
            'filter_columns'   => ['category'],
            'filter_operators' => ['='],
            'filter_values'    => [$this->id],
        ]);

        return Html::link($link, (string) $this->products_count, [
            'class'               => 'badge font-weight-bold ' . $badge,
            'data-bs-toggle'         => 'tooltip',
            'data-bs-original-title' => trans('plugins/ecommerce::product-categories.total_products', ['total' => $this->products_count]),
        ]);
    }

    protected static function boot()
    {
        parent::boot();

        self::deleting(function (ProductCategory $category) {
            $category->products()->detach();

            foreach ($category->children()->get() as $child) {
                $child->delete();
            }
        });
    }
}
