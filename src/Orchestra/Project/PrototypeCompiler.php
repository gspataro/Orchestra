<?php

namespace Orchestra\Project;

use Orchestra\Blueprint\NamespaceCollection;
use Orchestra\Project\Definition\MediaVariant\MediaVariantCollection;
use Orchestra\Project\Definition\Schema\SchemaCollection;
use Orchestra\Project\Definition\Source\SourceDefinitionCollection;
use Orchestra\Project\Factory\PrototypeFactory;
use Orchestra\Project\Interpreter\ConfigInterpreter;
use Orchestra\Project\Interpreter\MediaInterpreter;
use Orchestra\Project\Interpreter\SchemaInterpreter;
use Orchestra\Project\Interpreter\SourceInterpreter;

final class PrototypeCompiler
{
    /** @var InterpreterInterface[] */
    private array $interpreters = [
        ConfigInterpreter::class,
        SourceInterpreter::class,
        SchemaInterpreter::class,
        MediaInterpreter::class
    ];

    public function __construct(
        private readonly PrototypeFactory $prototypeFactory
    ) {
    }

    public function compile(NamespaceCollection $namespaces): Prototype
    {
        $context = new CompilerContext(
            new SourceDefinitionCollection(),
            new SchemaCollection(),
            new MediaVariantCollection(),
            new Config()
        );

        foreach ($this->interpreters as $interpreter) {
            $interpreter = new $interpreter();
            $interpreter->compile($namespaces->get($interpreter->namespace()), $context);
        }

        return $this->prototypeFactory->fromContext($context);
    }
}
