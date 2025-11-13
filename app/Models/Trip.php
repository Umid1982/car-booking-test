<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * Модель поездки / бронирования автомобиля.
 */
class Trip extends Model
{
    use HasFactory;

    /**
     * Массово заполняемые поля.
     *
     * @var list<string>
     */
    protected $fillable = [
        'car_id',
        'user_id',
        'start_at',
        'end_at',
        'status',
    ];

    /**
     * Приведение типов.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'start_at' => 'datetime',
        'end_at'   => 'datetime',
        'car_id'   => 'integer',
        'user_id'  => 'integer',
    ];

    /**
     * Автомобиль, участвующий в поездке.
     *
     * @return BelongsTo<Car>
     */
    public function car(): BelongsTo
    {
        return $this->belongsTo(Car::class);
    }

    /**
     * Пользователь, забронировавший поездку.
     *
     * @return BelongsTo<User>
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
