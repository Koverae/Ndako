<?php

namespace Modules\App\Traits\Files;

use Illuminate\Support\Arr;

trait HasImportLogic
{
    public static function processImportRow(array $data): array
    {
        // Default: return data as-is (override in models if needed)
        return $data;
    }

    public static function processImportPreviewRow(array $data, bool $forImport = false): array
    {
        // Default: return data as-is (override in models if needed)
        return $data;
    }

}
