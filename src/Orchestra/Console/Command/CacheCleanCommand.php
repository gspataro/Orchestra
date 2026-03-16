<?php

namespace Orchestra\Console\Command;

final class CacheCleanCommand extends BaseCommand
{
    protected string $name = 'cache:clean';
    protected ?string $description = 'Delete build cache';

    public function options(): array
    {
        return [
            'view-only' => [
                'type' => 'toggle',
                'description' => 'Delete only the theme cache'
            ]
        ];
    }

    public function main(): void
    {
        $this->output->print('{bold}Cleaning up cache...');

        /** @var \Orchestra\Compiler\Factory\BuildContextFactory */
        $contextFactory = $this->container->get('compiler.context.factory');
        $context = $contextFactory->make();
        $excluded = [];

        if ($this->argument('view-only') !== null) {
            $excluded[] = $context->paths()->cache('orchestra');
        }

        recursiveDelete(
            $context->paths()->cache(),
            false,
            $excluded
        );

        $this->output->print("{bold}{fg_green}Cache cleand.");
    }
}
