<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class Category extends Model
{
    use HasApiTokens, HasFactory, Notifiable, SoftDeletes;

    protected $primaryKey = 'uuid';
    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'parent_uuid',
        'description',
    ];

    public function children_category()
    {
        return $this->hasMany(Category::class, 'parent_uuid', 'uuid');
    }


    public function getAllChildrenUUIDs()
    {
        $childrenUUIDs = $this->children_category()->pluck('uuid')->toArray();
        foreach ($this->children_category as $child) {
            $childrenUUIDs = array_merge($childrenUUIDs, $child->getAllChildrenUUIDs());
        }

        return $childrenUUIDs;
    }
}