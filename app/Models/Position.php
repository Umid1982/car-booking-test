<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Position extends Model
{
    use HasFactory;

    /**
     * Массово заполняемые поля.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
    ];

    /**
     * Категории комфорта, доступные для данной должности.
     *
     * @return BelongsToMany<ComfortCategory>
     */
    public function comfortCategories(): BelongsToMany
    {
        return $this->belongsToMany(ComfortCategory::class, 'position_comfort_category')
            ->orderBy('level');
    }

    /**
     * Пользователи, которые имеют данную должность.
     *
     * @return HasMany<User>
     */
    public function users(): HasMany
    {
        return $this->hasMany(User::class);
    }
}
