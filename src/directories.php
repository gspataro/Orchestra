<?php

/**
 * @todo This is a temporary solution, need to change it
 */

/**
 * Root dir: /
 */

define("DIR_ROOT", getcwd());

/**
 * Source dir: /source
 */

define("DIR_SOURCE", DIR_ROOT . "/source");

/**
 * Output dir: /output
 */

define("DIR_OUTPUT", DIR_ROOT . "/public");

/**
 * Contents dir: /contents
 */
define("DIR_CONTENTS", DIR_ROOT . "/contents");

/**
 * Data dir: /contents/data
 */

define("DIR_DATA", DIR_CONTENTS . "/data");

/**
 * Languages dir: /contents/languages
 */

define("DIR_LANGS", DIR_CONTENTS . "/languages");

/**
 * Media dir: /contents/media
 */

define('DIR_MEDIA', DIR_CONTENTS . '/media');

/**
 * Resources dir: /resources
 */

define('DIR_RESOURCES', DIR_ROOT . '/resources');

/**
 * View dir: /resources/view
 */

define("DIR_VIEW", DIR_RESOURCES . "/view");

/**
 * Assets dir: /resources/assets
 */

define("DIR_ASSETS", DIR_RESOURCES . '/assets');
