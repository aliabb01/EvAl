<?php

namespace Database\Factories;

use App\Models\Agency;
use App\Models\Post;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class PostFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Post::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        $rand_user = User::all()->random();

        return [
            'user_id' => $rand_user,
            'agency_id' => $rand_user->agency,

            'estate_type' => $this->faker->randomElement(['new apartment', 'apartment', 'house-villa', 'office', 'garage']),
            'city' => $this->faker->city(),
            'area' => $this->faker->numberBetween($min = 10, $max = 500),
            'room_count' => $this->faker->numberBetween($min = 1, $max = 9),
            'address' => $this->faker->address(),
            'district' => $this->faker->city(),

            'apartment_floor' => $this->faker->numberBetween($min = 1, $max = 20),
            'total_floors' => $this->faker->numberBetween($min = 5, $max = 20),

            'description' => $this->faker->text(),
            'price' => $this->faker->numberBetween($min = 50000, $max = 1000000),

            'contact_email' => $this->faker->safeEmail(),
            'contact_phone_number' => $this->faker->phoneNumber(),

            'trade_type' => $this->faker->randomElement(['sell', 'rent']),
            'realtor_type' => $this->faker->randomElement(['self', 'agent']),

            'is_vip' => $this->faker->boolean(30),
            'has_voucher' => $this->faker->boolean(50),

            'views_total' => $this->faker->numberBetween($min = 10, $max = 100),
            'views_today' => $this->faker->numberBetween($min = 0, $max = 10),
            
            'updated_at' => $this->faker->date(),
            'created_at' => $this->faker->date()
        ];
    }
}
