<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class CarResource extends JsonResource
{
    /**
     * @param Request $request
     * @return array
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'license_plate' => $this->license_plate,
            'year' => $this->year,
            'color' => $this->color,
            'model' => $this->whenLoaded('carModel', fn() => $this->getModelData()),
            'driver' => $this->whenLoaded('driver', fn() => $this->getDriverData()),
        ];
    }

    /**
     * @return array
     */
    private function getModelData(): array
    {
        return [
            'id' => $this->carModel->id,
            'brand' => $this->carModel->brand,
            'name' => $this->carModel->name,
            'comfort_category' => $this->getComfortCategoryData(),
        ];
    }

    /**
     * @return array|null
     */
    private function getComfortCategoryData(): ?array
    {
        if (!$this->carModel->relationLoaded('comfortCategory')) {
            return null;
        }

        $category = $this->carModel->comfortCategory;

        return [
            'id'   => $category->id,
            'name' => $category->name,
        ];

    }

    /**
     * @return array|null
     */
    private function getDriverData(): ?array
    {
        if (!$this->driver) {
            return null;
        }

        return [
            'id'    => $this->driver->id,
            'name'  => $this->driver->name,
            'phone' => $this->driver->phone,
        ];
    }
}
