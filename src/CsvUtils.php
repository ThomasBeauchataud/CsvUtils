<?php

/*
 * This file is part of the tbcd/csv-utils package.
 *
 * (c) Thomas Beauchataud <thomas.beauchataud@yahoo.fr>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 *
 * Author Thomas Beauchataud
 */

namespace TBCD\CsvUtils;

class CsvUtils
{

    /**
     * Read a CSV file and return his content as an array by row
     * If there is a header, an associative array is returned
     *
     * @param string $filePath The path of the csv file
     * @param string $separator The separator of the csv file
     * @param bool $header If the file has a header or not
     * @return array
     */
    public static function readCsv(string $filePath, string $separator = ';', bool $header = false): array
    {
        $data = [];
        $headers = [];

        if (($handle = fopen($filePath, "r")) !== false) {
            while (($row = fgetcsv($handle, null, $separator)) !== false) {
                if ($header && !empty($headers)) {
                    $headers = $row;
                    continue;
                }
                $rowData = [];
                foreach ($row as $index => $value) {
                    $rowData[$headers[$index] ?? $index] = $value;
                }
                $data[] = $rowData;
            }
            fclose($handle);
        }

        return $data;
    }

    /**
     * Write a CSV file
     *
     * @param array $data The data to put in the file
     * @param string $filePath The path of the created csv file
     * @param string $separator The separator of the csv file
     * @param bool $header If the file has a header or not. If true, the header is generated with index keys of the data array
     * @return void
     */
    public static function writeCsv(array $data, string $filePath, string $separator = ';', bool $header = false): void
    {
        $fp = fopen($filePath, 'w');

        foreach ($data as $fields) {
            if ($header) {
                fputcsv($fp, array_keys($fields), $separator);
                $header = false;
            }
            fputcsv($fp, $fields, $separator);
        }

        fclose($fp);
    }
}