<?php

namespace App\Enums;

enum LogSource: string
{
    case API = 'api';
    case WEB = 'web';
}