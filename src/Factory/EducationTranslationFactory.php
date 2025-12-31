<?php

namespace App\Factory;

use App\Entity\EducationTranslation;
use Zenstruck\Foundry\Persistence\PersistentProxyObjectFactory;

/**
 * @extends PersistentProxyObjectFactory<EducationTranslation>
 */
final class EducationTranslationFactory extends PersistentProxyObjectFactory
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
        return EducationTranslation::class;
    }

    /**
     * @see https://symfony.com/bundles/ZenstruckFoundryBundle/current/index.html#model-factories
     *
     * @todo add your default values here
     */
    protected function defaults(): array|callable
    {
        return [
            'degree'=>self::faker()->text(),
            'school'=>self::faker()->name(),
            'description'=>self::faker()->text(),
            'location'=>self::faker()->text(),
            'locale'=>LocaleFactory::new(),
        ];
    }

    /**
     * @see https://symfony.com/bundles/ZenstruckFoundryBundle/current/index.html#initialization
     */
    protected function initialize(): static
    {
        return $this
            // ->afterInstantiate(function(EducationTranslation $educationTranslation): void {})
        ;
    }
}
