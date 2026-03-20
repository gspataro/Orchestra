<?php

namespace Orchestra\View\Elements\Svg;

use Orchestra\View\ViewElement;

final class SvgElement extends ViewElement
{
    protected string $name = 'svg';

    protected function data(array $data = []): array
    {
        $src = $data['src'] ?? null;

        if (!$src || !is_file($src)) {
            return $data;
        }

        $raw = file_get_contents($src);

        if (preg_match("/viewBox=\"(.*?)\"/", $raw, $viewBox)) {
            $data['viewBox'] = $viewBox[1] ?? '';
        }

        if (preg_match("/<svg[^>]*>(.*?)<\/svg>/s", $raw, $innerSvg)) {
            $data['innerSvg'] = $innerSvg[1] ?? '';
        }

        return $data;
    }
}
