<?php

namespace App\Factory;

use App\Entity\ExperienceTranslation;
use Zenstruck\Foundry\Persistence\PersistentProxyObjectFactory;

/**
 * @extends PersistentProxyObjectFactory<ExperienceTranslation>
 */
final class ExperienceTranslationFactory extends PersistentProxyObjectFactory
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
        return ExperienceTranslation::class;
    }

    /**
     * @see https://symfony.com/bundles/ZenstruckFoundryBundle/current/index.html#model-factories
     *
     * @todo add your default values here
     */
    protected function defaults(): array|callable
    {
        return [
            'title' => self::faker()->text(255),
            'description' => self::faker()->text(255),
            'location' => self::faker()->text(255),
            'locale' => LocaleFactory::randomOrCreate(),
        ];
    }

    /**
     * @see https://symfony.com/bundles/ZenstruckFoundryBundle/current/index.html#initialization
     */
    protected function initialize(): static
    {
        return $this
            // ->afterInstantiate(function(ExperienceTranslation $experienceTranslation): void {})
        ;
    }
}
