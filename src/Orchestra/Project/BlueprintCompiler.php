<?php

namespace Orchestra\Project;

use Orchestra\Blueprint\Blueprint;
use Orchestra\Project\Definition\MediaVariant\MediaVariantCollection;
use Orchestra\Project\Definition\Schema\SchemaCollection;
use Orchestra\Project\Definition\Source\SourceCollection;
use Orchestra\Project\Factory\PrototypeFactory;
use Orchestra\Project\Interpreter\ConfigInterpreter;
use Orchestra\Project\Interpreter\MediaInterpreter;
use Orchestra\Project\Interpreter\SchemaInterpreter;
use Orchestra\Project\Interpreter\SourceInterpreter;

final class BlueprintCompiler
{
    private array $interpreters = [
        ConfigInterpreter::class,
        SourceInterpreter::class,
        SchemaInterpreter::class,
        MediaInterpreter::class
    ];

    public function __construct(
        private readonly Blueprint $blueprint,
        private readonly PrototypeFactory $prototypeFactory
    ) {
    }

    public function compile(): Prototype
    {
        $context = new CompilerContext(
            new SourceCollection(),
            new SchemaCollection(),
            new MediaVariantCollection(),
            new Config()
        );

        foreach ($this->interpreters as $interpreter) {
            new $interpreter()->compile($this->blueprint, $context);
        }

        return $this->prototypeFactory->fromContext($context);
    }
}
