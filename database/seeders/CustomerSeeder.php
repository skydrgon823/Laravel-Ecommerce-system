<?php

namespace Database\Seeders;

use Botble\Base\Supports\BaseSeeder;
use Botble\Ecommerce\Models\Address;
use Botble\Ecommerce\Models\Customer;
use Faker\Factory;

class CustomerSeeder extends BaseSeeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $this->uploadFiles('customers');

        $faker = Factory::create();

        Customer::truncate();
        Address::truncate();

        $customers = [
            'john.smith@botble.com',
            'customer@botble.com',
        ];

        foreach ($customers as $item) {
            $customer = Customer::create([
                'name'     => $faker->name,
                'email'    => $item,
                'password' => bcrypt('12345678'),
                'phone'    => $faker->e164PhoneNumber,
                'avatar'   => 'customers/' . $faker->numberBetween(1, 8) . '.jpg',
                'dob'      => now()->subYears(rand(20, 50))->subDays(rand(1, 30)),
            ]);

            $customer->confirmed_at = now();
            $customer->save();

            Address::create([
                'name'        => $customer->name,
                'phone'       => $faker->e164PhoneNumber,
                'email'       => $customer->email,
                'country'     => $faker->countryCode,
                'state'       => $faker->state,
                'city'        => $faker->city,
                'address'     => $faker->streetAddress,
                'zip_code'    => $faker->postcode,
                'customer_id' => $customer->id,
                'is_default'  => true,
            ]);

            Address::create([
                'name'        => $customer->name,
                'phone'       => $faker->e164PhoneNumber,
                'email'       => $customer->email,
                'country'     => $faker->countryCode,
                'state'       => $faker->state,
                'city'        => $faker->city,
                'address'     => $faker->streetAddress,
                'zip_code'    => $faker->postcode,
                'customer_id' => $customer->id,
                'is_default'  => false,
            ]);
        }

        for ($i = 0; $i < 8; $i++) {
            $customer = Customer::create([
                'name'     => $faker->name,
                'email'    => $faker->unique()->safeEmail,
                'password' => bcrypt('12345678'),
                'phone'    => $faker->e164PhoneNumber,
                'avatar'   => 'customers/' . ($i + 1) . '.jpg',
                'dob'      => now()->subYears(rand(20, 50))->subDays(rand(1, 30)),
            ]);

            $customer->confirmed_at = now();
            $customer->save();

            Address::create([
                'name'        => $customer->name,
                'phone'       => $faker->e164PhoneNumber,
                'email'       => $customer->email,
                'country'     => $faker->countryCode,
                'state'       => $faker->state,
                'city'        => $faker->city,
                'address'     => $faker->streetAddress,
                'zip_code'    => $faker->postcode,
                'customer_id' => $customer->id,
                'is_default'  => true,
            ]);
        }
    }
}
