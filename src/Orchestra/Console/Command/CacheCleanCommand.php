<?php

namespace Orchestra\Console\Command;

final class CacheCleanCommand extends BaseCommand
{
    protected string $name = 'cache:clean';
    protected ?string $description = 'Delete build cache';

    public function main(): void
    {
        $this->output->print('{bold}Cleaning up cache...');

        /** @var \Orchestra\Compiler\BuildContext */
        $context = $this->container->get('compiler.context');

        recursiveDelete(
            $context->paths()->cache(),
            false,
            []
        );

        $this->output->print("{bold}{fg_green}Cache cleand.");
    }
}
