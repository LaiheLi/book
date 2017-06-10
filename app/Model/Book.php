<?php
namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class Book extends Model
{
    const TYPE_CHAPTER = 1;
    const TYPE_SECTION = 2;

    protected $guarded = [];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function chapters()
    {
        return $this->hasMany(Chapter::class);
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function sections()
    {
        return $this->hasMany(Section::class);
    }

    public function getTypeNameAttribute()
    {
        return $this->type == static::TYPE_CHAPTER ? '章-节' : '节';
    }

    public function getChapterNumAttribute()
    {
        return $this->chapters()->count();
    }

    public function getSectionNumAttribute()
    {
        return $this->sections()->count();
    }

    /**
     * 书籍分类
     *
     * @param \Illuminate\Database\Eloquent\Builder $model
     * @param null                                  $catalog
     *
     * @return Model
     */
    public function scopeCatalog($model, $catalog = NULL)
    {
        if ($catalog) {
            return $model->where('catalog', $catalog);
        } else {
            return $model;
        }
    }
}
