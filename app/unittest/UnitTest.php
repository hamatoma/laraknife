<?php

namespace Unittest;

class UnitTest
{
    public function buildPointer(string $expected, string $actual): string
    {
        $col = 0;
        $count1 = strlen($expected);
        $count2 = strlen($actual);
        while ($col < $count1 && $col < $count2 && $expected[$col] === $actual[$col]) {
            $col++;
        }
        return str_repeat("=", $col) . "^";
    }
    public function differentMultiLines(string &$expected, string &$actual): void
    {
        $lines1 = explode("\n", $expected);
        $lines2 = explode("\n", $actual);
        for ($ix = 0; $ix < count($lines1); $ix++) {
            $expected1 = $lines1[$ix];
            if (count($lines2) >= $ix) {
                echo ("- $expected1\n+++ missing line\n");
                break;
            }
            $actual1 = $lines2[$ix];
            if ($expected1 !== $actual1) {
                $pointer = $this->buildPointer($expected1, $actual1);
                echo ("- $expected1\n+ $actual1\n=$pointer");
                break;
            }
        }
    }
    public function assertEquals($expected, $actual)
    {
        if ($expected !== $actual) {
            if (is_string($expected) && is_string($actual)) {
                if (strpos($expected, "\n") !== false || strpos($actual, "\n") !== false) {
                    $this->differentMultiLines($expected, $actual);
                } else {
                    $pointer = $this->buildPointer($expected, $actual);
                    echo ("- $expected\n+ $actual\n=$pointer");
                }

            } else {
                echo "Expected: $expected\n";
                echo "Actual: $actual\n";
            }
            echo "Different:\n$expected\n";
            echo "Actual: $actual\n";
        } elseif ($expected != $actual) {
            echo "! Expected: $expected != $actual\n";
        }
    }
}
