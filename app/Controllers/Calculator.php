<?php

namespace App\Controllers;
use App\Models\ClientModel;

class Calculator extends BaseController
{

    protected $clientModel;

public function __construct()
{
    $this->clientModel = new ClientModel();
}

    // 📄 Main Page
public function index()
{
    $data['clients'] = $this->clientModel->findAll();

    return view('calculator/index', $data);
}

    // 📤 File Upload + Calculation
public function upload()
{
    $client = $this->request->getPost('client');
    $metal  = $this->request->getPost('metal');

    $file = $this->request->getFile('txt_file');

    if ($file && $file->isValid() && !$file->hasMoved()) {

        $content = file_get_contents($file->getTempName());
        $lines = explode("\n", trim($content));

        $resultData = [];

        // 🔵 DEFAULT RANGES (NON-ROUND)
        $ranges = [
            ['min'=>0.50,'max'=>2.30,'label'=>'0.50-2.30'],
            ['min'=>2.40,'max'=>2.75,'label'=>'2.40-2.75'],
            ['min'=>2.80,'max'=>3.30,'label'=>'2.80-3.30'],
            ['min'=>3.30,'max'=>999,'label'=>'Above-3.30'],
        ];

        // 🟣 ROUND RANGES (MAIN FIX)
        $roundRanges = [
            ['min'=>0.5,'max'=>1.1,'label'=>'0.8-1.1'],
            ['min'=>1.2,'max'=>10,'label'=>'Above-1.2'],
        ];

        // 🔵 ROUND EXACT MAPPING (IMPORTANT)
        $roundMap = [
            0.5=>0.0025,
            0.6=>0.0025,
            0.7=>0.0050,
            0.8=>0.0025,
            0.9=>0.0040,
            1.0=>0.0050,
            1.1=>0.0055,
            1.2=>0.0075,
            1.3=>0.0100,
            1.4=>0.0120,
            1.5=>0.0150,
            1.6=>0.0190
        ];

        // 🟠 SHAPE FACTORS (NON-ROUND)
        $shapeFactor = [
            'TRIANGLE' => 0.0030,
            'TRILLION CURVED' => 0.0030,
            'HEART' => 0.0035,
            'RADIANT' => 0.0036,
            'RADIANT SQUARE' => 0.0036,
            'EMERALD' => 0.0035,
            'EMERALD SQUARE' => 0.0035,
            'OCTAGON' => 0.0035,
            'MARQUISE' => 0.0032,
            'PEAR' => 0.0031,
            'PRINCESS' => 0.0040,
            'CUSHION' => 0.0034,
            'CUSHION SQUARE' => 0.0034,
            'OVAL' => 0.0033,
            'BAGUETTE STRAIGHT' => 0.0020,
        ];

        foreach ($lines as $line) {

            $line = trim($line);
            if (empty($line)) continue;
            if (stripos($line, 'Total Gems') !== false) continue;

            // ✅ Qty
            preg_match('/^\s*(\d+)/', $line, $qtyMatch);
            $qty = isset($qtyMatch[1]) ? (int)$qtyMatch[1] : 0;

            // ✅ Shape
            $parts = explode(',', $line);
            $shape = strtoupper(trim($parts[2] ?? 'UNKNOWN'));

            // ✅ Sizes
            preg_match('/X=([\d\.]+)/', $line, $xMatch);
            preg_match('/Y=([\d\.]+)/', $line, $yMatch);
            preg_match('/Z=([\d\.]+)/', $line, $zMatch);

            $x = isset($xMatch[1]) ? (float)$xMatch[1] : 0;
            $y = isset($yMatch[1]) ? (float)$yMatch[1] : 0;
            $z = isset($zMatch[1]) ? (float)$zMatch[1] : 0;

            // ✅ Original CTW (for display only)
            preg_match('/total\s+([\d\.]+)ct/i', $line, $ctwMatch);
            $ctw = isset($ctwMatch[1]) ? (float)$ctwMatch[1] : 0;

            if (!$qty || !$x) continue;

            // 🎯 Select correct ranges
            $activeRanges = ($shape == 'ROUND') ? $roundRanges : $ranges;

            // ✅ INIT SHAPE
            if (!isset($resultData[$shape])) {
                foreach ($activeRanges as $r) {
                    $resultData[$shape][$r['label']] = [
                        'items' => [],
                        'gems' => 0,
                        'weight' => 0
                    ];
                }
            }

            // 🎯 CALCULATION
            if ($shape == 'ROUND') {

                $size = round($x, 1);
                $perStone = $roundMap[$size] ?? 0;
                $calculatedWeight = $qty * $perStone;

            } else {

                $factor = $shapeFactor[$shape] ?? 0.003;
                $calculatedWeight = $x * $y * $z * $factor * $qty;
                $size = $x;
            }

            // ✅ RANGE ASSIGN
            foreach ($activeRanges as $r) {
                if ($size >= $r['min'] && $size <= $r['max']) {

                    $resultData[$shape][$r['label']]['items'][] = [
                        'size' => $size,
                        'qty'  => $qty,
                        'ctw'  => $ctw
                    ];

                    $resultData[$shape][$r['label']]['gems'] += $qty;
                    $resultData[$shape][$r['label']]['weight'] += $calculatedWeight;

                    break;
                }
            }
        }

        // 🔥 FINAL ROUNDING
        foreach ($resultData as $shape => $rangesData) {
            foreach ($rangesData as $range => $values) {
                $resultData[$shape][$range]['weight'] = round($values['weight'], 3);
            }
        }

        $data['clients'] = $this->clientModel->findAll();
        $data['resultData'] = $resultData;
        $data['client'] = $client;
        $data['metal']  = $metal;

        return view('calculator/index', $data);
    }

    return redirect()->back()->with('error', 'File upload failed');
}
}