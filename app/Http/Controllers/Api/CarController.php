<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Car\AvailableCarsRequest;
use App\Http\Resources\CarResource;
use App\Http\Traits\ApiResponseHelper;
use App\Services\CarService;
use Illuminate\Http\JsonResponse;

class CarController extends Controller
{
    use ApiResponseHelper;

    public function __construct(protected readonly CarService $carService)
    {
    }

    /**
     * Получить список доступных автомобилей
     *
     * @param AvailableCarsRequest $request
     * @return JsonResponse
     */
    public function available(AvailableCarsRequest $request): JsonResponse
    {
        return $this->successResponse(
            CarResource::collection($this->carService->getAvailableCars($request->filters())),
            message: 'Список доступных автомобилей успешно получен'
        );
    }
}
