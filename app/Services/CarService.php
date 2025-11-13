<?php

namespace App\Services;

use App\Models\User;
use App\Repositories\Car\CarRepositoryInterface;
use App\Repositories\User\UserRepositoryInterface;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Collection;

class CarService
{
    /**
     * @param CarRepositoryInterface $carRepository
     * @param UserRepositoryInterface $userRepository
     */
    public function __construct(
        private readonly CarRepositoryInterface $carRepository,
        private readonly UserRepositoryInterface $userRepository
    ) {
    }

    /**
     * Получить список доступных автомобилей для пользователя.
     *
     * @param array $filters
     * @return Collection
     */
    public function getAvailableCars(array $filters): Collection
    {
        $user = $this->getUserWithPosition($filters['user_id']);

        if (!$this->hasAvailableCategories($user)) {
            return collect();
        }

        $query = $this->buildQuery($user, $filters);

        return $this->carRepository->get($query);
    }

    /**
     * @param int $userId
     * @return User|null
     */
    private function getUserWithPosition(int $userId): ?User
    {
        return $this->userRepository->getUserWithPosition($userId);
    }

    /**
     * @param User|null $user
     * @return bool
     */
    private function hasAvailableCategories(?User $user): bool
    {
        return $user && $user->position && $user->position->comfortCategories->isNotEmpty();
    }

    /**
     * @param User $user
     * @param array $filters
     * @return Builder
     */
    private function buildQuery(User $user, array $filters): Builder
    {
        $availableCategoryIds = $user->position->comfortCategories->pluck('id')->toArray();

        $query = $this->carRepository->baseQuery();

        $query = $this->carRepository->filterByComfortCategory(
            $query,
            $availableCategoryIds,
            $filters['comfort_category_id'] ?? null
        );

        $query = $this->carRepository->filterByModel(
            $query,
            $filters['car_model_id'] ?? null
        );

        return $this->carRepository->excludeBusyCars(
            $query,
            $filters['start_at'],
            $filters['end_at']
        );
    }
}
