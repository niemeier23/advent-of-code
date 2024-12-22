<?php namespace day3;

date_default_timezone_set('America/North_Dakota/Center');

$sum = 0;
$products = [];

$dataFilePath = './corruptedMemory.txt';

$reportFilePath = 'reports/day3_mull_it_over_' . date('Ymd_His') . '.txt';
$memory = file_get_contents($dataFilePath);
if($memory !== false)
{
	$matches = [];
	$memoryLength = strlen($memory);

	// MATCH EVERYTHING THAT'S IN THE DISABLED BLOCK,
	//  ENDING IN 'do()' or a newline.
	$disabledSequencePattern = '/don\'t\(\)((.|\n?)*?)(do\(\)|$)/s';
	// REPLACE IT WITH EMPTY STRING
	$memorySansDisabledSequences = preg_replace($disabledSequencePattern, '', $memory);


	preg_match_all(
		'/mul\((\d{1,3}),(\d{1,3})\)/',
		$memorySansDisabledSequences,
		$matches
	);

	foreach($matches[0] as $k => $match)
	{
		$arg0 = intval($matches[1][$k]);
		$arg1 = intval($matches[2][$k]);

		$product = call_user_func_array('day3\\mul', [ $arg0, $arg1 ]);
		$sum += $product;

		$products[] = [
			$matches[0][$k],
			$product,
			$sum
		];
	}

	echo "Memory instructions [" . count($matches[0]) . "], sum [" . number_format($sum) . "]";

	writeReport($reportFilePath, $products, $sum);
}
else
{
	echo "Error reading \"corrupted memory\" into this program.\n";
}


echo "\n";
echo 'DONE';
echo "\n";


function mul(int $n1, int $n2)
{
	return ($n1 * $n2);
}



function writeReport(string $reportFilePath, array $products, int $sum)
{
	$fileHandle = fopen($reportFilePath, 'w');
	if($fileHandle !== false)
	{
		fwrite($fileHandle, "Memory instructions [" . count($products) . "], sum [" . number_format($sum) . "]\n");

		foreach($products as $i => $details)
		{
			fwrite(
				$fileHandle,
				sprintf(
					"%-5d%-14s%+12s%+12s\n",
					$i, 
					$details[0],
					number_format($details[1]),
					number_format($details[2])
				)
			);
		}

		fclose($fileHandle);
	}
}

?>
