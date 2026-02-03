<?php

namespace GSpataro\Application\Component;

use GSpataro\DependencyInjection\Container;
use GSpataro\Localization\Locales;
use GSpataro\Localization\Language;
use GSpataro\Solista\Component;

final class LocalizationComponent extends Component
{
    public function register(Container $container): void
    {
        $container->variable('langsPath', DIR_LANGS);

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
