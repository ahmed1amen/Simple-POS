<?php

namespace Database\Factories;

use App\Models\Bill;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

class BillFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Bill::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {

      $total=  $this->faker->numberBetween(1000,10000);
      $paid =  $this->faker->randomElement([$total/3 , $total]);
      $rest =   $total - $paid;
        return [
            'customername' => $this->faker->name,
            'total' => $total,
            'paid' => $paid,
            'rest' => $rest,
            'deliverday' => 1,


        ];
    }

    /**
     * Indicate that the model's email address should be unverified.
     *
     * @return \Illuminate\Database\Eloquent\Factories\Factory
     */
    public function unverified()
    {
        return $this->state(function (array $attributes) {
            return [
                'email_verified_at' => null,
            ];
        });
    }
}
