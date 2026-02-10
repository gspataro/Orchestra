<?php

namespace Orchestra\Library;

enum ErrorEnum: string
{
    case SpacesInFilename = 'Contents sources filenames should not contain spaces.';
}
