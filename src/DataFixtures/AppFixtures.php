<?php

namespace App\DataFixtures;

use App\Entity\Ingredient;
use App\Entity\Recipe;
use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Faker\Factory;
use Faker\Generator;

class AppFixtures extends Fixture
{
    private Generator $faker;

    public function __construct()
    {
        $this->faker = Factory::create('fr_FR');
    }

    public function load(ObjectManager $manager): void
    {
//        Ingredients
        $ingredients = [];
        for ($i = 1; $i <= 50; $i++) {
            $ingredient = new Ingredient();
            $ingredient->setName($this->faker->word())
                ->setPrice($this->faker->randomFloat(2, 1, 200));
            $ingredients[] = $ingredient;
            $manager->persist($ingredient);
        }

//        Recipes
        for ($j = 0; $j < 33; $j++) {
            $recipe = new Recipe();
            $recipe->setName($this->faker->sentence(mt_rand(1, 3)))
                ->setTime(mt_rand(0, 1) == 1 ? mt_rand(1, 1440) : null)
                ->setPeople(mt_rand(0, 1) == 1 ? mt_rand(1, 50) : null)
                ->setDifficulty(mt_rand(0, 1) == 1 ? mt_rand(1, 5) : null)
                ->setDescription($this->faker->text(mt_rand(20, 1500)))
                ->setPrice(mt_rand(0, 1) == 1 ? mt_rand(2, 1000 * 2) / 2 : null) //mt_rand(2,1000*2)/2 -> return a float number
                ->setIsFavorite(mt_rand(0, 1) == 1);

            for ($k = 0; $k < mt_rand(3, 15); $k++) {
                $recipe->addIngredient($ingredients[mt_rand(0, count($ingredients) - 1)]);
            }

            $manager->persist($recipe);
        }

// Users
        for ($i = 0; $i < 10; $i++) {
            $user = new User();
            $user->setFullName($this->faker->name())
                ->setUsername(mt_rand(0, 1) == 1 ? $this->faker->userName() : null)
                ->setEmail($this->faker->email())
                ->setRoles(['ROLE_USER'])
                ->setPlainPassword('1234567');
            $manager->persist($user);
        }

        $manager->flush();
    }
}
