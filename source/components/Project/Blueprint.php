<?php

namespace GSpataro\Project;

use GSpataro\Utilities\DotNavigator;

final class Blueprint extends DotNavigator
{
    protected bool $readOnly = true;

    /**
     * Initialize blueprint
     *
     * @param string $filePath
     */

    public function __construct(string $filePath)
    {
        if (!is_file($filePath)) {
            throw new Exception\InvalidBlueprintException(
                "Blueprint file not found: '{$filePath}'."
            );
        }

        $extension = pathinfo($filePath, PATHINFO_EXTENSION);

        if ($extension !== 'json') {
            throw new Exception\InvalidBlueprintException(
                "Invalid blueprint provided. The blueprint file must be a json file."
            );
        }

        $rawJson = file_get_contents($filePath);
        $data = $this->readData($rawJson);

        if (!isset($data['website'])) {
            $data['website'] = [];
        }

        $data['website']['url'] = getenv('WEBSITE_URL');

        $this->fill($data);
    }

    /**
     * Read user given data
     *
     * @param string $rawJson
     * @return array
     */

    private function readData(string $rawJson): array
    {
        $data = json_decode($rawJson, true);
        return $data;
    }
}
