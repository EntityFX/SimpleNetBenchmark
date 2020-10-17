<?php

define("DEBUG", true);

require_once("Writer.php");
require_once("BenchmarkBase.php");
require_once("ArithmeticsBase.php");
require_once("ArithmeticsBenchmark.php");
require_once("MathBase.php");
require_once("MathBenchmark.php");
require_once("CallBenchmark.php");
require_once("IfElseBenchmark.php");
require_once("StringManipulationBase.php");
require_once("StringManipulation.php");
require_once("MemoryBenchmarkBase.php");
require_once("MemoryBenchmark.php");
require_once("RandomMemoryBenchmarkBase.php");
require_once("RandomMemoryBenchmark.php");
require_once("Dhrystone2.php");
require_once("DhrystoneBenchmark.php");

use EntityFX\NetBenchmark\Core\Writer;
use EntityFX\NetBenchmark\Core\Generic\ArithmeticsBenchmark;
use EntityFX\NetBenchmark\Core\Generic\MathBenchmark;
use EntityFX\NetBenchmark\Core\Generic\CallBenchmark;
use EntityFX\NetBenchmark\Core\Generic\IfElseBenchmark;
use EntityFX\NetBenchmark\Core\Generic\StringManipulation;
use EntityFX\NetBenchmark\Core\Generic\MemoryBenchmark;
use EntityFX\NetBenchmark\Core\Generic\RandomMemoryBenchmark;
use EntityFX\NetBenchmark\Core\Dhrystone\DhrystoneBenchmark;

function writeResult($writer, $benchResult)
{
    $writer->WriteTitle("%-30s", $benchResult["BenchmarkName"]);
    $writer->WriteValue("%.2f ms", $benchResult["Elapsed"]);
    $writer->WriteValue("%15.2f pts", $benchResult["Points"]);
    $writer->WriteValue("%15.2f %s", $benchResult["Result"], $benchResult["Units"]);
    $writer->WriteNewLine();
}


$writer = new Writer();

$benchmarks = [
    new MemoryBenchmark($writer, true),
    new RandomMemoryBenchmark($writer, true),
    new DhrystoneBenchmark($writer, true),
    new ArithmeticsBenchmark($writer, true),
    new MathBenchmark($writer, true),
    new CallBenchmark($writer, true),
    new IfElseBenchmark($writer, true),
    new StringManipulation($writer, true)
];

$writer->WriteHeader("Warmup");
foreach ($benchmarks as $key => $bench) {
    $bench->Warmup();
    $writer->Write(".");
}

$writer->WriteNewLine();
$writer->WriteHeader("Bench");

$total = 0;
$totalPoints = 0;
$i = 1;
$result = [];

foreach ($benchmarks as $key => $bench) {
    $writer->WriteHeader("[$i] {$bench->Name}");
    $r = $bench->Bench();
    $total += $r["Elapsed"];
    $totalPoints += $r["Points"];
    writeResult($writer, $r);

    $result[] = $r;
    $i++;
}

