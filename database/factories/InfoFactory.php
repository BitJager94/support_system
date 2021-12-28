<?php

namespace Database\Factories;

use App\Models\Info;
use Illuminate\Database\Eloquent\Factories\Factory;

class InfoFactory extends Factory
{

    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Info::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        static $user_id = 1;   
        return [
            'first_name'        => $this->faker->firstname('male'),
            'last_name'         => $this->faker->lastname('male'),
            'company_name'      => null,
            'phone'             => "0948948262",
            'gender'            => 'male',
            'age'               => '11',
            'address'           => 'none eeeee',
            'website_url'       => 'www.website.com',
            'user_id'           => $user_id++,
        ];
    }
}
