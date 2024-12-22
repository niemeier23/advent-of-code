<?php namespace day5;

date_default_timezone_set('America/North_Dakota/Center');

$sum = 0;
$products = [];

$pageRules = [];
$updatePageNumbers = [];

$pageOrderRulesFilePath = 'C:\code\advent-of-code\2024\day5-print_queue\print_order_rules.txt';
$updatePageNumbersFilePath = 'C:\code\advent-of-code\2024\day5-print_queue\update_page_numbers.txt';

$reportFilePath = 'reports/day5_printQueue_' . date('Ymd_His') . '.txt';

$rules_fileHandle = fopen($pageOrderRulesFilePath, "r");
if($rules_fileHandle !== false)
{
	$delimiter = '|';
	while(getNextLine($rules_fileHandle, $line))
	{
		$pageRules[] = array_map('intval', explode($delimiter, $line));
	}

	fclose($rules_fileHandle);
}


$pages_fileHandle = fopen($updatePageNumbersFilePath, "r");
if($pages_fileHandle !== false)
{
	$delimiter = ',';
	while(getNextLine($pages_fileHandle, $line))
	{
		$updatePageNumbers[] = array_map('intval', explode($delimiter, $line));
	}

	fclose($pages_fileHandle);
}







// echo json_encode($pageRules);
// echo "Sum of middle page numbers [" . number_format($sum) . "], rightly ordered [], total [].";

// writeReport($reportFilePath, $products, $sum);

echo "\n";
echo 'DONE';
echo "\n";


function getNextLine($fileHandle, &$line)
{
	$line = fgets($fileHandle);

	return ($line !== false);
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
