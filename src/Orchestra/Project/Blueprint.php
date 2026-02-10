<?php

namespace Orchestra\Project;

use Orchestra\Utilities\DotNavigator;

final class Blueprint extends DotNavigator
{
    protected bool $readOnly = true;

    /**
     * Initialize blueprint
     *
     * @param array $data
     */

    public function init(array $data)
    {
        if (!isset($data['website'])) {
            $data['website'] = [];
        }

        $data['website']['url'] = getenv('WEBSITE_URL');

        $this->fill($data);
    }
}
