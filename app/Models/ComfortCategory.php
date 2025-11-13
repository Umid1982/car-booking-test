<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * Модель категории комфорта автомобиля.
 */
class ComfortCategory extends Model
{
    use HasFactory;

    /**
     * Массово заполняемые поля.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'level',
    ];

    /**
     * Приведение типов.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'level' => 'integer',
    ];

    /**
     * Должности, которым доступна данная категория комфорта.
     *
     * @return BelongsToMany<Position>
     */
    public function positions(): BelongsToMany
    {
        return $this->belongsToMany(Position::class, 'position_comfort_category')
            ->orderBy('name');
    }

    /**
     * Модели автомобилей, относящиеся к этой категории комфорта.
     *
     * @return HasMany<CarModel>
     */
    public function carModels(): HasMany
    {
        return $this->hasMany(CarModel::class)
            ->orderBy('name');
    }
}
