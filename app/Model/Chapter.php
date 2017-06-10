<?php
namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class Chapter extends Model
{
    protected $guarded = [];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function book()
    {
        return $this->belongsTo(Book::class);
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function sections()
    {
        return $this->hasMany(Section::class);
    }

    public function getSectionNumAttribute()
    {
        return $this->sections()->count();
    }
}

