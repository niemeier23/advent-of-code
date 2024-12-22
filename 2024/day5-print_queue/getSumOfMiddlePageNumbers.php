<?php namespace day5;

date_default_timezone_set('America/North_Dakota/Center');

$sum = 0;
$products = [];

$pageRules = [];
$updatePageNumbers = [];

$pageOrderRulesFilePath = 'C:\code\advent-of-code\2024\day5-print_queue\print_order_rules.txt';
$updatePageNumbersFilePath = 'C:\code\advent-of-code\2024\day5-print_queue\update_page_numbers.txt';

$reportFilePath = 'reports/day5_printQueue_' . date('Ymd_His') . '.txt';

$fileHandle = fopen($pageOrderRulesFilePath, "r");
if($fileHandle !== false)
{
	// $i = 0;
	while(getNextLine($fileHandle, $line))
	{
		/*
		++$i;
		if($i >= 25)
		{
			break;
		}
		*/

		/*
		The first section specifies the page ordering rules, one per line. 
		The first rule, 47|53, means that if an update includes both page number 47 and page number 53, 
		 then page number 47 must be printed at some point before page number 53. 
		 (47 doesn't necessarily need to be immediately before 53; other pages are allowed to be between them.)
		*/

		$pageRules[] = array_map('intval', explode('|', $line));
	}

	fclose($fileHandle);


	echo json_encode($pageRules);
	// echo "Sum of middle page numbers [" . number_format($sum) . "], rightly ordered [], total [].";

	// writeReport($reportFilePath, $products, $sum);
}


$fileHandle = fopen($updatePageNumbersFilePath, "r");
if($fileHandle !== false)
{
	// $i = 0;
	while(getNextLine($fileHandle, $line))
	{
		/*
		++$i;
		if($i >= 25)
		{
			break;
		}
		*/

		/*
		The second section specifies the page numbers of each update. 
		Because most safety manuals are different, the pages needed in the updates are different too. 
		The first update, [75,47,61,53,29], means that the update consists of page numbers 75, 47, 61, 53, and 29.
		*/

		$updatePageNumbers[] = array_map('intval', explode(',', $line));
	}

	fclose($fileHandle);
	

	echo json_encode($pageRules);
	// echo "Sum of middle page numbers [" . number_format($sum) . "], rightly ordered [], total [].";

	// writeReport($reportFilePath, $products, $sum);
}
else
{
	echo "Error reading \"corrupted memory\" into this program.\n";
}


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
