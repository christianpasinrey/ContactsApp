<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\ContactData;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Contact>
 */
class ContactDataFactory extends Factory
{
    public $types;
    public function __construct()
    {
        $this->types = [];
        $this->types['email'] = ContactData::TYPE_EMAIL;
        $this->types['phone'] = ContactData::TYPE_PHONE;
        $this->types['linkedin'] = ContactData::TYPE_LINKED;
        $this->types['facebook'] = ContactData::TYPE_FACEBOOK;
        $this->types['instagram'] = ContactData::TYPE_INSTAGRAM;
    }

    public function getRandomType()
    {
        $keys = array_keys($this->types);
        $random_key = $keys[array_rand($keys)];
        return $this->types[$random_key];
    }

    public function generateValue($type):string
    {
        $value = '';
        switch ($type) {
            case ContactData::TYPE_EMAIL:
                $value = $this->faker->email();
                break;
            case ContactData::TYPE_PHONE:
                $value = $this->faker->phoneNumber();
                break;
            case ContactData::TYPE_LINKED:
                $value = $this->faker->word.$this->faker->word;
                break;
            case ContactData::TYPE_FACEBOOK:
                $value = $this->faker->word.$this->faker->word;
                break;
            case ContactData::TYPE_INSTAGRAM:
                $value = $this->faker->word.$this->faker->word;
                break;
            default:
                $value = $this->faker->word.$this->faker->word;
                break;
        }
        return $value;
    }
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'label' => $this->faker->word(),
            'type' => $this->getRandomType(),
            'value' => $this->generateValue($this->getRandomType()),
            'is_main' => $this->faker->boolean(),
        ];
    }
}
