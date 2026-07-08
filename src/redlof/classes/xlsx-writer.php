<?php
declare(strict_types=1);

/**
 * Minimal XLSX writer (single sheet, shared strings).
 * No external dependencies; relies on ZipArchive.
 */
final class CdpiXlsxException extends RuntimeException {}

final class CdpiXlsxWriter {
    private const NS_MAIN = 'http://schemas.openxmlformats.org/spreadsheetml/2006/main';

    /**
     * @param string[] $headers
     * @param array<int, array<int, scalar|null>> $rows
     */
    public static function build(array $headers, array $rows): string {
        if (!class_exists('ZipArchive')) {
            throw new CdpiXlsxException('ZipArchive is required to generate XLSX.');
        }

        $xmlHeader = '<?xml version="1.0" encoding="UTF-8" standalone="yes"?>';
        $sheet = array_merge([$headers], $rows);
        $shared = [];
        $sharedIndex = [];
        $cellXmlRows = [];

        foreach ($sheet as $r => $row) {
            $cellXml = [];
            foreach (array_values($row) as $c => $value) {
                $ref = self::col($c) . ($r + 1);
                $text = self::toString($value);
                if (!array_key_exists($text, $sharedIndex)) {
                    $sharedIndex[$text] = count($shared);
                    $shared[] = $text;
                }
                $cellXml[] = '<c r="' . $ref . '" t="s"><v>' . $sharedIndex[$text] . '</v></c>';
            }
            $cellXmlRows[] = '<row r="' . ($r + 1) . '">' . implode('', $cellXml) . '</row>';
        }

        $sharedXml = $xmlHeader
            . '<sst xmlns="' . self::NS_MAIN . '" count="' . count($shared) . '" uniqueCount="' . count($shared) . '">'
            . implode('', array_map(static fn(string $s): string => '<si><t>' . self::xml($s) . '</t></si>', $shared))
            . '</sst>';

        $sheetXml = $xmlHeader
            . '<worksheet xmlns="' . self::NS_MAIN . '">'
            . '<sheetData>' . implode('', $cellXmlRows) . '</sheetData>'
            . '</worksheet>';

        $workbookXml = $xmlHeader
            . '<workbook xmlns="' . self::NS_MAIN . '" xmlns:r="http://schemas.openxmlformats.org/officeDocument/2006/relationships">'
            . '<sheets><sheet name="Sheet1" sheetId="1" r:id="rId1"/></sheets>'
            . '</workbook>';

        $contentTypesXml = $xmlHeader
            . '<Types xmlns="http://schemas.openxmlformats.org/package/2006/content-types">'
            . '<Default Extension="rels" ContentType="application/vnd.openxmlformats-package.relationships+xml"/>'
            . '<Default Extension="xml" ContentType="application/xml"/>'
            . '<Override PartName="/xl/workbook.xml" ContentType="application/vnd.openxmlformats-officedocument.spreadsheetml.sheet.main+xml"/>'
            . '<Override PartName="/xl/worksheets/sheet1.xml" ContentType="application/vnd.openxmlformats-officedocument.spreadsheetml.worksheet+xml"/>'
            . '<Override PartName="/xl/sharedStrings.xml" ContentType="application/vnd.openxmlformats-officedocument.spreadsheetml.sharedStrings+xml"/>'
            . '</Types>';

        $relsXml = $xmlHeader
            . '<Relationships xmlns="http://schemas.openxmlformats.org/package/2006/relationships">'
            . '<Relationship Id="rId1" Type="http://schemas.openxmlformats.org/officeDocument/2006/relationships/officeDocument" Target="xl/workbook.xml"/>'
            . '</Relationships>';

        $workbookRelsXml = $xmlHeader
            . '<Relationships xmlns="http://schemas.openxmlformats.org/package/2006/relationships">'
            . '<Relationship Id="rId1" Type="http://schemas.openxmlformats.org/officeDocument/2006/relationships/worksheet" Target="worksheets/sheet1.xml"/>'
            . '<Relationship Id="rId2" Type="http://schemas.openxmlformats.org/officeDocument/2006/relationships/sharedStrings" Target="sharedStrings.xml"/>'
            . '</Relationships>';

        $tmp = tempnam(sys_get_temp_dir(), 'cdpi_xlsx_');
        if ($tmp === false) {
            throw new CdpiXlsxException('Failed to create temp file for XLSX.');
        }

        $zip = new ZipArchive();
        if ($zip->open($tmp, ZipArchive::OVERWRITE) !== true) {
            @unlink($tmp);
            throw new CdpiXlsxException('Failed to open zip for XLSX.');
        }

        $zip->addFromString('[Content_Types].xml', $contentTypesXml);
        $zip->addFromString('_rels/.rels', $relsXml);
        $zip->addFromString('xl/workbook.xml', $workbookXml);
        $zip->addFromString('xl/_rels/workbook.xml.rels', $workbookRelsXml);
        $zip->addFromString('xl/worksheets/sheet1.xml', $sheetXml);
        $zip->addFromString('xl/sharedStrings.xml', $sharedXml);
        $zip->close();

        $bin = (string) file_get_contents($tmp);
        @unlink($tmp);
        if ($bin === '') {
            throw new CdpiXlsxException('Failed to read generated XLSX.');
        }
        return $bin;
    }

    private static function toString(mixed $value): string {
        $out = '';
        if ($value === null) {
            $out = '';
        } elseif (is_bool($value)) {
            $out = $value ? 'Yes' : 'No';
        } elseif (is_int($value) || is_float($value)) {
            $out = (string) $value;
        } else {
            $out = trim((string) $value);
        }
        return $out;
    }

    private static function xml(string $s): string {
        return htmlspecialchars($s, ENT_QUOTES | ENT_XML1, 'UTF-8');
    }

    private static function col(int $index): string {
        $index += 1;
        $out = '';
        while ($index > 0) {
            $mod = ($index - 1) % 26;
            $out = chr(65 + $mod) . $out;
            $index = intdiv($index - 1, 26);
        }
        return $out;
    }
}

