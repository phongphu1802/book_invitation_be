<?php

namespace App\Enums;

enum ConfigEnum: string
{
    case SYSTEM = 'system';
    case PUBLIC = 'public';
    case BOOLEAN = 'boolean';
    case TEXT = 'text';
    case IMAGE = 'image';
    case IMAGES = 'images';
    case NUMBER = 'number';
}