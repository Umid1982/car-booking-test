<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * Модель конкретного автомобиля (конфигурация, водитель, статус).
 */
class Car extends Model
{
    use HasFactory;

    /**
     * Массово заполняемые поля.
     *
     * @var list<string>
     */
    protected $fillable = [
        'car_model_id',
        'driver_id',
        'license_plate',
        'year',
        'color',
        'status',
    ];

    /**
     * Приведение типов.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'car_model_id' => 'integer',
        'driver_id'    => 'integer',
        'year'         => 'integer',
    ];

    /**
     * Модель автомобиля (бренд + название).
     *
     * @return BelongsTo<CarModel>
     */
    public function carModel(): BelongsTo
    {
        return $this->belongsTo(CarModel::class, 'car_model_id');
    }

    /**
     * Водитель, закреплённый за автомобилем.
     *
     * @return BelongsTo<Driver>
     */
    public function driver(): BelongsTo
    {
        return $this->belongsTo(Driver::class);
    }

    /**
     * Поездки, связанные с автомобилем.
     *
     * @return HasMany<Trip>
     */
    public function trips(): HasMany
    {
        return $this->hasMany(Trip::class)
            ->orderBy('start_at');
    }
}
