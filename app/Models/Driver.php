<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * Модель водителя, закреплённого за автомобилями.
 */
class Driver extends Model
{
    use HasFactory;

    /**
     * Массово заполняемые поля.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'phone',
        'license_number',
    ];

    /**
     * Приведение типов.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'phone' => 'string',
        'license_number' => 'string',
    ];

    /**
     * Автомобили, закреплённые за водителем.
     *
     * @return HasMany<Car>
     */
    public function cars(): HasMany
    {
        return $this->hasMany(Car::class)->orderBy('license_plate');
    }
}
