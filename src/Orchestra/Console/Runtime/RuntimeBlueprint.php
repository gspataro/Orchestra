<?php

namespace Orchestra\Console\Runtime;

use Orchestra\Pipeline\BuildContext;
use Orchestra\Project\Blueprint;

class RuntimeBlueprint extends Runtime
{
    public function __construct(
        private readonly BuildContext $context,
        private readonly Blueprint $blueprint
    ) {
    }

    public function main(): bool
    {
        $blueprintFile = $this->context->paths->root('blueprint.json');

        if (!is_file($blueprintFile)) {
            $this->output->print('{bold}{fg_red}Blueprint file not found in project root.');
            return false;
        }

        $rawBlueprint = file_get_contents($blueprintFile);

        if (!json_validate($rawBlueprint)) {
            $this->output->print('{bold}{fg_red}Invalid blueprint. A valid blueprint must be a JSON object.');
            return false;
        }

        $data = json_decode($rawBlueprint, true);
        $this->blueprint->init($data);

        return true;
    }
}
