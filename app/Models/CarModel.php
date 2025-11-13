<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * Модель автомобильной модели (бренд + название).
 */
class CarModel extends Model
{
    use HasFactory;

    /**
     * Массово заполняемые поля.
     *
     * @var list<string>
     */
    protected $fillable = [
        'brand',
        'name',
        'comfort_category_id',
    ];

    /**
     * Приведение типов.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'comfort_category_id' => 'integer',
    ];

    /**
     * Категория комфорта, к которой относится данная модель автомобиля.
     *
     * @return BelongsTo<ComfortCategory>
     */
    public function comfortCategory(): BelongsTo
    {
        return $this->belongsTo(ComfortCategory::class);
    }

    /**
     * Автомобили, относящиеся к данной модели.
     *
     * @return HasMany<Car>
     */
    public function cars(): HasMany
    {
        return $this->hasMany(Car::class)
            ->orderBy('license_plate');
    }
}
