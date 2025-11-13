<?php

namespace App\Repositories\Car;

use App\Models\Car;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;

class CarRepository implements CarRepositoryInterface
{
    /**
     * @return Builder
     */
    public function baseQuery(): Builder
    {
        return Car::query()
            ->with([
                'carModel.comfortCategory',
                'driver',
            ])
            ->where('status', 'available');
    }

    /**
     * @param Builder $query
     * @param int|null $carModelId
     * @return Builder
     */
    public function filterByModel(Builder $query, ?int $carModelId): Builder
    {
        return $query->when($carModelId, fn($q) => $q->where('car_model_id', $carModelId));
    }

    /**
     * @param Builder $query
     * @param array $allowedCategoryIds
     * @param int|null $comfortCategoryId
     * @return Builder
     */
    public function filterByComfortCategory(
        Builder $query,
        array $allowedCategoryIds,
        ?int $comfortCategoryId
    ): Builder {
        return $query->whereHas('carModel', function ($q) use ($allowedCategoryIds, $comfortCategoryId) {
            $q->whereIn('comfort_category_id', $allowedCategoryIds);

            $q->when($comfortCategoryId, function ($subQuery) use ($comfortCategoryId) {
                $subQuery->where('comfort_category_id', $comfortCategoryId);
            });
        });
    }

    /**
     * @param Builder $query
     * @param Carbon|string|\DateTime $startAt
     * @param Carbon|string|\DateTime $endAt
     * @return Builder
     */
    public function excludeBusyCars(Builder $query, Carbon|string|\DateTime $startAt, Carbon|string|\DateTime $endAt): Builder
    {
        return $query->whereDoesntHave('trips', function ($q) use ($startAt, $endAt) {
            $q->whereIn('status', ['planned', 'in_progress'])
                ->where('start_at', '<', $endAt)
                ->where('end_at', '>', $startAt);
        });
    }

    /**
     * @param Builder $query
     * @return Collection
     */
    public function get(Builder $query): Collection
    {
        return $query->get();
    }
}
