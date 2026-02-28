<?php

namespace Orchestra\Media;

final class PolicyCollection
{
    /** @var string[] */
    private array $map = [];

    /** @var PolicyInterface[] */
    private array $policies = [];

    public function has(string $tag): bool
    {
        return isset($this->policies[$tag]);
    }

    public function add(PolicyInterface $policy): void
    {
        if (!isset($this->policies[$policy::class])) {
            $this->policies[$policy::class] = $policy;
        }

        foreach ($policy->supports() as $mimeType) {
            $this->map[$mimeType] = $policy::class;
        }
    }

    public function getFor(string $mimeType): ?PolicyInterface
    {
        if (!isset($this->map[$mimeType])) {
            return null;
        }

        return $this->policies[$this->map[$mimeType]];
    }
}
