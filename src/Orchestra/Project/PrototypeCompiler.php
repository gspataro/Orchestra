<?php

namespace Orchestra\Project;

use Orchestra\Blueprint\NamespaceCollection;
use Orchestra\Project\Definition\MediaVariant\MediaVariantDefinitionCollection;
use Orchestra\Project\Definition\Schema\SchemaDefinitionCollection;
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
            new SchemaDefinitionCollection(),
            new MediaVariantDefinitionCollection(),
            new Config()
        );

        foreach ($this->interpreters as $interpreter) {
            $interpreter = new $interpreter();
            $interpreter->compile($namespaces->get($interpreter->namespace()), $context);
        }

        return $this->prototypeFactory->fromContext($context);
    }
}
