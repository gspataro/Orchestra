<?php

namespace Orchestra\Localization;

use GSpataro\DependencyInjection\Container;
use Orchestra\Localization\Locales;
use Orchestra\Localization\Language;
use Orchestra\Application\Component;

final class LocalizationComponent extends Component
{
    public function register(Container $container): void
    {
        $container->variable('langsPath', '');

        $container->add('locales', fn(): object => new Locales());

        $container->add('lang', function ($container, $args): object {
            return new Language(
                $args['langKey'],
                $container->variable('langsPath') . '/' . $args['langKey']
            );
        }, false);
    }

    public function boot(Container $container): void
    {
        $blueprint = $container->get('project.blueprint');
        $locales = $container->get('locales');

        if (!$blueprint->get('languages')) {
            return;
        }

        foreach ($blueprint->get('languages') as $langKey) {
            $locales->addLanguage(
                $container->get('lang', [
                    'langKey' => $langKey
                ])
            );
        }
    }
}
