<?php

namespace Orchestra\Content;

enum ErrorEnum: string
{
    case SpacesInFilename = 'Contents sources filenames should not contain spaces.';
}
