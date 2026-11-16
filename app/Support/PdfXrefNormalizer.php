<?php

namespace App\Support;

use RuntimeException;

/**
 * Konversi PDF ber-xref-stream (PDF 1.5+) ke classic xref (PDF 1.4) agar
 * terbaca parser gratis FPDI/mPDF. Mendukung xref stream sederhana
 * (tanpa /Prev dan tanpa object stream).
 */
class PdfXrefNormalizer
{
    public function normalize(string $sourcePath): string
    {
        $raw = file_get_contents($sourcePath);

        if ($raw === false) {
            throw new RuntimeException('Tidak dapat membaca file PDF.');
        }

        if (strpos($raw, '/Type /XRef') === false && strpos($raw, '/Type/XRef') === false) {
            return $sourcePath;
        }

        $tempPath = sys_get_temp_dir().DIRECTORY_SEPARATOR.'xnorm_'.uniqid('', true).'.pdf';
        file_put_contents($tempPath, $this->convert($raw));

        return $tempPath;
    }

    protected function convert(string $raw): string
    {
        $startxrefPos = strrpos($raw, 'startxref');

        if ($startxrefPos === false || ! preg_match('/startxref\s+(\d+)/', $raw, $m, 0, $startxrefPos)) {
            throw new RuntimeException('startxref tidak ditemukan.');
        }

        $xrefOffset = (int) $m[1];
        $chunk = substr($raw, $xrefOffset);

        if (! preg_match('/^\s*(\d+)\s+(\d+)\s+obj\s*<<(.*?)>>\s*stream\s*/s', $chunk, $om)) {
            throw new RuntimeException('Header xref stream tidak valid.');
        }

        $xrefObjNum = (int) $om[1];
        $dictText = $om[3];
        $streamDataStart = $xrefOffset + strlen($om[0]);

        $num = function (string $key, string $dict): ?int {
            return preg_match('/\/'.$key.'\s+([0-9]+)/', $dict, $mm) ? (int) $mm[1] : null;
        };
        $rawField = function (string $key, string $dict): ?string {
            return preg_match('/\/'.$key.'\s+(\[[^\]]*\]|\d+\s+\d+\s+R|[^\/\s]+)/s', $dict, $mm) ? $mm[1] : null;
        };

        $size = $num('Size', $dictText) ?? 0;
        $length = $num('Length', $dictText) ?? 0;
        $root = $rawField('Root', $dictText);
        $info = $rawField('Info', $dictText);
        $id = $rawField('ID', $dictText);
        $wRaw = $rawField('W', $dictText);
        $indexRaw = $rawField('Index', $dictText);

        if ($size <= 0 || $length <= 0) {
            throw new RuntimeException('Size/Length xref stream tidak valid.');
        }

        preg_match_all('/\d+/', $wRaw ?? '', $wm);

        if (count($wm[0]) < 3) {
            throw new RuntimeException('Nilai /W xref stream tidak valid.');
        }

        $w1 = (int) $wm[0][0];
        $w2 = (int) $wm[0][1];
        $w3 = (int) $wm[0][2];
        $entrySize = $w1 + $w2 + $w3;

        $streamData = substr($raw, $streamDataStart, $length);

        if (strpos($dictText, '/FlateDecode') !== false) {
            $dec = @gzuncompress($streamData);

            if ($dec === false) {
                throw new RuntimeException('Gagal dekompresi FlateDecode.');
            }

            $streamData = $dec;
        }

        $ranges = [];

        if ($indexRaw !== null && $indexRaw !== '') {
            preg_match_all('/\d+/', $indexRaw, $im);

            for ($i = 0, $n = count($im[0]); $i < $n; $i += 2) {
                $ranges[] = [(int) $im[0][$i], (int) $im[0][$i + 1]];
            }
        } else {
            $ranges[] = [0, $size];
        }

        $entries = [];
        $pos = 0;

        foreach ($ranges as [$start, $count]) {
            for ($j = 0; $j < $count; $j++) {
                $e = substr($streamData, $pos, $entrySize);
                $pos += $entrySize;

                if (strlen($e) < $entrySize) {
                    throw new RuntimeException('Data xref stream terpotong.');
                }

                $type = ord($e[0]);
                $f2 = 0;

                for ($k = 0; $k < $w2; $k++) {
                    $f2 = ($f2 << 8) | ord($e[$w1 + $k]);
                }

                $f3 = 0;

                for ($k = 0; $k < $w3; $k++) {
                    $f3 = ($f3 << 8) | ord($e[$w1 + $w2 + $k]);
                }

                $entries[$start + $j] = ['type' => $type, 'offset' => $f2, 'gen' => $f3];
            }
        }

        $body = substr($raw, 0, $xrefOffset);
        $body = preg_replace('/^%PDF-\d+\.\d+/', '%PDF-1.4', $body, 1);

        $maxObj = $xrefObjNum - 1;
        $table = "xref\n0 ".($maxObj + 1)."\n";

        for ($i = 0; $i <= $maxObj; $i++) {
            $e = $entries[$i] ?? ['type' => 0, 'offset' => 0, 'gen' => 65535];

            if ($e['type'] === 1 && $e['offset'] > 0) {
                $table .= sprintf("%010d %05d n \n", $e['offset'], $e['gen']);
            } else {
                $table .= "0000000000 65535 f \n";
            }
        }

        $trailer = "trailer\n<< /Size ".($maxObj + 1);

        if ($root !== null) {
            $trailer .= " /Root $root";
        }

        if ($info !== null) {
            $trailer .= " /Info $info";
        }

        if ($id !== null) {
            $trailer .= " /ID $id";
        }

        $trailer .= " >>\n";

        $tableOffset = strlen($body);

        return $body.$table.$trailer."startxref\n".$tableOffset."\n%%EOF\n";
    }
}
