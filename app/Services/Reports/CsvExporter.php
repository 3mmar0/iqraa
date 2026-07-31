<?php

namespace App\Services\Reports;

class CsvExporter
{
    /**
     * @param  list<array<int|string, scalar|null>>  $rows
     */
    public function exportToPath(array $rows, string $path): string
    {
        $dir = dirname($path);
        if (! is_dir($dir)) {
            mkdir($dir, 0755, true);
        }

        $handle = fopen($path, 'w');
        if ($handle === false) {
            throw new \RuntimeException("Unable to open path for CSV export: {$path}");
        }

        try {
            foreach ($rows as $row) {
                fputcsv($handle, array_values($row));
            }
        } finally {
            fclose($handle);
        }

        return $path;
    }
}