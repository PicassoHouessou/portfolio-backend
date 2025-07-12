<?php

namespace App\Factory;

use App\Entity\Experience;
use Zenstruck\Foundry\Persistence\PersistentProxyObjectFactory;

/**
 * @extends PersistentProxyObjectFactory<Experience>
 */
final class ExperienceFactory extends PersistentProxyObjectFactory
{
    /**
     * @see https://symfony.com/bundles/ZenstruckFoundryBundle/current/index.html#factories-as-services
     *
     * @todo inject services if required
     */
    public function __construct()
    {
    }

    public static function class(): string
    {
        return Experience::class;
    }

    /**
     * @see https://symfony.com/bundles/ZenstruckFoundryBundle/current/index.html#model-factories
     *
     * @todo add your default values here
     */
    protected function defaults(): array|callable
    {
        return [
            'user' => UserFactory::new(),
            'company'=>self::faker()->company(),
            'description'=>self::faker()->text(),
            'startAt'=>self::faker()->dateTime(),
            'endAt'=>self::faker()->dateTime(),
            'experience'=>ExperienceFactory::new(),
            'locationType'=>LocationTypeFactory::randomOrCreate(),
        ];
    }

    /**
     * @see https://symfony.com/bundles/ZenstruckFoundryBundle/current/index.html#initialization
     */
    protected function initialize(): static
    {
        return $this
            // ->afterInstantiate(function(Experience $experience): void {})
        ;
    }
}
