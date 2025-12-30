<?php

namespace GSpataro\Application\Component;

use GSpataro\Localization\Locales;
use GSpataro\Localization\Language;
use GSpataro\DependencyInjection\Component;

final class LocalizationComponent extends Component
{
    public function register(): void
    {
        $this->container->variable('langsPath', DIR_LANGS);

        $this->container->add('locales', fn(): object => new Locales());

        $this->container->add('lang', function ($container, $args): object {
            return new Language(
                $args['langKey'],
                $container->variable('langsPath') . '/' . $args['langKey']
            );
        }, false);
    }

    public function boot(): void
    {
        $blueprint = $this->container->get('project.blueprint');
        $locales = $this->container->get('locales');

        foreach ($blueprint->get('languages') as $langKey) {
            $locales->addLanguage(
                $this->container->get('lang', [
                    'langKey' => $langKey
                ])
            );
        }
    }
}
