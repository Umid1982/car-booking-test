<?php

namespace App\Repositories\Car;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;

interface CarRepositoryInterface
{
    /**
     * Создать базовый запрос для доступных автомобилей.
     *
     * @return Builder
     */
    public function baseQuery(): Builder;

    /**
     * Фильтровать по модели автомобиля.
     *
     * @param Builder $query
     * @param int|null $carModelId
     * @return Builder
     */
    public function filterByModel(Builder $query, ?int $carModelId): Builder;

    /**
     * Фильтровать по категории комфорта.
     *
     * @param Builder $query
     * @param array $allowedCategoryIds
     * @param int|null $comfortCategoryId
     * @return Builder
     */
    public function filterByComfortCategory(
        Builder $query,
        array $allowedCategoryIds,
        ?int $comfortCategoryId
    ): Builder;

    /**
     * Исключить занятые автомобили в указанном интервале.
     *
     * @param Builder $query
     * @param Carbon|string|\DateTime $startAt
     * @param Carbon|string|\DateTime $endAt
     * @return Builder
     */
    public function excludeBusyCars(
        Builder $query,
        Carbon|string|\DateTime $startAt,
        Carbon|string|\DateTime $endAt
    ): Builder;

    /**
     * Выполнить запрос и получить коллекцию.
     *
     * @param Builder $query
     * @return Collection
     */
    public function get(Builder $query): Collection;
}

