<?php

namespace Tests\Feature;

use App\Models\Car;
use App\Models\CarModel;
use App\Models\ComfortCategory;
use App\Models\Driver;
use App\Models\Position;
use App\Models\Trip;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class CarAvailableTest extends TestCase
{
    use RefreshDatabase;

    private User $user;
    private ComfortCategory $firstCategory;
    private ComfortCategory $secondCategory;
    private ComfortCategory $thirdCategory;
    private Position $position;
    private CarModel $carModel1;
    private CarModel $carModel2;
    private Driver $driver1;
    private Driver $driver2;
    private Car $car1;
    private Car $car2;

    protected function setUp(): void
    {
        parent::setUp();

        // Создаем категории комфорта
        $this->firstCategory = ComfortCategory::create(['name' => 'Первая', 'level' => 1]);
        $this->secondCategory = ComfortCategory::create(['name' => 'Вторая', 'level' => 2]);
        $this->thirdCategory = ComfortCategory::create(['name' => 'Третья', 'level' => 3]);

        // Создаем должность с доступом только к первой категории
        $this->position = Position::create(['name' => 'Менеджер']);
        $this->position->comfortCategories()->attach($this->firstCategory->id);

        // Создаем пользователя с должностью
        $this->user = User::create([
            'name' => 'Test User',
            'email' => 'test@example.com',
            'password' => Hash::make('password'),
            'position_id' => $this->position->id,
        ]);

        // Создаем модели автомобилей
        $this->carModel1 = CarModel::create([
            'brand' => 'Mercedes-Benz',
            'name' => 'S-Class',
            'comfort_category_id' => $this->firstCategory->id,
        ]);

        $this->carModel2 = CarModel::create([
            'brand' => 'BMW',
            'name' => '7 Series',
            'comfort_category_id' => $this->firstCategory->id,
        ]);

        // Создаем водителей
        $this->driver1 = Driver::create([
            'name' => 'Иван Иванов',
            'phone' => '+79990000001',
            'license_number' => 'AB123456',
        ]);

        $this->driver2 = Driver::create([
            'name' => 'Петр Петров',
            'phone' => '+79990000002',
            'license_number' => 'CD789012',
        ]);

        // Создаем автомобили
        $this->car1 = Car::create([
            'car_model_id' => $this->carModel1->id,
            'driver_id' => $this->driver1->id,
            'license_plate' => 'A001AA',
            'year' => 2023,
            'color' => 'Black',
            'status' => 'available',
        ]);

        $this->car2 = Car::create([
            'car_model_id' => $this->carModel2->id,
            'driver_id' => $this->driver2->id,
            'license_plate' => 'A002AA',
            'year' => 2022,
            'color' => 'White',
            'status' => 'available',
        ]);
    }

    /**
     * Тест: фильтрация по модели автомобиля
     */
    public function test_filter_by_car_model(): void
    {
        $token = $this->user->createToken('test-token')->plainTextToken;

        $startAt = Carbon::now()->addDay()->format('Y-m-d H:i:s');
        $endAt = Carbon::now()->addDays(2)->format('Y-m-d H:i:s');

        // Запрос без фильтра - должны вернуться оба автомобиля
        $response = $this->withHeader('Authorization', 'Bearer ' . $token)
            ->getJson("/api/cars/available?start_at={$startAt}&end_at={$endAt}");

        $response->assertStatus(200)
            ->assertJsonStructure([
                'success',
                'data' => [
                    '*' => ['id', 'license_plate', 'year', 'color', 'model', 'driver']
                ]
            ]);

        $data = $response->json('data');
        $this->assertCount(2, $data);

        // Запрос с фильтром по модели - должен вернуться только один автомобиль
        $response = $this->withHeader('Authorization', 'Bearer ' . $token)
            ->getJson("/api/cars/available?start_at={$startAt}&end_at={$endAt}&car_model_id={$this->carModel1->id}");

        $response->assertStatus(200);
        $data = $response->json('data');
        $this->assertCount(1, $data);
        $this->assertEquals($this->car1->id, $data[0]['id']);
        $this->assertEquals($this->carModel1->id, $data[0]['model']['id']);
    }

    /**
     * Тест: исключение занятых автомобилей
     */
    public function test_exclude_busy_cars(): void
    {
        $token = $this->user->createToken('test-token')->plainTextToken;

        $startAt = Carbon::now()->addDay();
        $endAt = Carbon::now()->addDays(2);

        // Создаем поездку для car1 в запрашиваемом интервале
        Trip::create([
            'car_id' => $this->car1->id,
            'user_id' => $this->user->id,
            'start_at' => $startAt->copy()->addHours(2),
            'end_at' => $startAt->copy()->addHours(6),
            'status' => 'planned',
        ]);

        // Запрос должен вернуть только car2, так как car1 занят
        $response = $this->withHeader('Authorization', 'Bearer ' . $token)
            ->getJson("/api/cars/available?start_at={$startAt->format('Y-m-d H:i:s')}&end_at={$endAt->format('Y-m-d H:i:s')}");

        $response->assertStatus(200);
        $data = $response->json('data');
        $this->assertCount(1, $data);
        $this->assertEquals($this->car2->id, $data[0]['id']);

        // Проверяем, что поездка со статусом 'in_progress' также исключает автомобиль
        Trip::create([
            'car_id' => $this->car2->id,
            'user_id' => $this->user->id,
            'start_at' => $startAt->copy()->addHours(1),
            'end_at' => $startAt->copy()->addHours(5),
            'status' => 'in_progress',
        ]);

        // Теперь не должно быть доступных автомобилей
        $response = $this->withHeader('Authorization', 'Bearer ' . $token)
            ->getJson("/api/cars/available?start_at={$startAt->format('Y-m-d H:i:s')}&end_at={$endAt->format('Y-m-d H:i:s')}");

        $response->assertStatus(200);
        $data = $response->json('data');
        $this->assertCount(0, $data);
    }

    /**
     * Тест: доступность только категории комфорта должности
     */
    public function test_only_available_comfort_categories_for_position(): void
    {
        $token = $this->user->createToken('test-token')->plainTextToken;

        // Создаем автомобиль второй категории (недоступной для должности пользователя)
        $carModelSecondCategory = CarModel::create([
            'brand' => 'Toyota',
            'name' => 'Camry',
            'comfort_category_id' => $this->secondCategory->id,
        ]);

        $carSecondCategory = Car::create([
            'car_model_id' => $carModelSecondCategory->id,
            'driver_id' => $this->driver1->id,
            'license_plate' => 'B001BB',
            'year' => 2022,
            'color' => 'Blue',
            'status' => 'available',
        ]);

        $startAt = Carbon::now()->addDay()->format('Y-m-d H:i:s');
        $endAt = Carbon::now()->addDays(2)->format('Y-m-d H:i:s');

        // Запрос должен вернуть только автомобили первой категории
        $response = $this->withHeader('Authorization', 'Bearer ' . $token)
            ->getJson("/api/cars/available?start_at={$startAt}&end_at={$endAt}");

        $response->assertStatus(200);
        $data = $response->json('data');

        // Должны быть только автомобили первой категории
        $this->assertGreaterThan(0, count($data));
        foreach ($data as $car) {
            $this->assertEquals($this->firstCategory->id, $car['model']['comfort_category']['id']);
            $this->assertNotEquals($carSecondCategory->id, $car['id']);
        }

        // Проверяем фильтр по категории комфорта
        $response = $this->withHeader('Authorization', 'Bearer ' . $token)
            ->getJson("/api/cars/available?start_at={$startAt}&end_at={$endAt}&comfort_category_id={$this->firstCategory->id}");

        $response->assertStatus(200);
        $data = $response->json('data');
        $this->assertGreaterThan(0, count($data));
        foreach ($data as $car) {
            $this->assertEquals($this->firstCategory->id, $car['model']['comfort_category']['id']);
        }
    }

    /**
     * Тест: пользователь без должности не видит автомобили
     */
    public function test_user_without_position_sees_no_cars(): void
    {
        $userWithoutPosition = User::create([
            'name' => 'User Without Position',
            'email' => 'noposition@example.com',
            'password' => Hash::make('password'),
            'position_id' => null,
        ]);

        $token = $userWithoutPosition->createToken('test-token')->plainTextToken;

        $startAt = Carbon::now()->addDay()->format('Y-m-d H:i:s');
        $endAt = Carbon::now()->addDays(2)->format('Y-m-d H:i:s');

        $response = $this->withHeader('Authorization', 'Bearer ' . $token)
            ->getJson("/api/cars/available?start_at={$startAt}&end_at={$endAt}");

        $response->assertStatus(200);
        $data = $response->json('data');
        $this->assertCount(0, $data);
    }

    /**
     * Тест: автомобили в статусе maintenance не возвращаются
     */
    public function test_cars_in_maintenance_status_are_excluded(): void
    {
        // Создаем автомобиль в статусе maintenance
        $carMaintenance = Car::create([
            'car_model_id' => $this->carModel1->id,
            'driver_id' => $this->driver1->id,
            'license_plate' => 'M001MM',
            'year' => 2023,
            'color' => 'Red',
            'status' => 'maintenance',
        ]);

        $token = $this->user->createToken('test-token')->plainTextToken;

        $startAt = Carbon::now()->addDay()->format('Y-m-d H:i:s');
        $endAt = Carbon::now()->addDays(2)->format('Y-m-d H:i:s');

        $response = $this->withHeader('Authorization', 'Bearer ' . $token)
            ->getJson("/api/cars/available?start_at={$startAt}&end_at={$endAt}");

        $response->assertStatus(200);
        $data = $response->json('data');

        // Автомобиль в maintenance не должен быть в списке
        $carIds = array_column($data, 'id');
        $this->assertNotContains($carMaintenance->id, $carIds);
    }
}
