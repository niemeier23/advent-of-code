<?php namespace day5;

date_default_timezone_set('America/North_Dakota/Center');

$sum = 0;
$products = [];

$pageRules = [];
$updatePageNumbers = [];
$incorrectUpdates = [];

$pageOrderRulesFilePath = 'C:\code\advent-of-code\2024\day5\print_order_rules.txt';
$updatePageNumbersFilePath = 'C:\code\advent-of-code\2024\day5\update_page_numbers.txt';

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

$incorrectCount = 0;
$incorrectIndexes = [];
$correctlyReorderedPageNumbers = [];

$pageRulesCount = count($pageRules);

foreach($updatePageNumbers as $i => &$pageNumbers)
{
	$isCorrectlyOrdered = true;

	for($z = 0; $z < $pageRulesCount; ++$z)
	{
		$firstNbrIndex = array_search($pageRules[$z][0], $pageNumbers);
		$secondNbrIndex = array_search($pageRules[$z][1], $pageNumbers);

		if($firstNbrIndex !== false && $secondNbrIndex !== false)
		{
			$isCorrectlyOrdered = ($firstNbrIndex < $secondNbrIndex);
			if(!$isCorrectlyOrdered)
			{
				correctPageOrder($pageRules, $pageNumbers);

				++$incorrectCount;
				$incorrectIndexes[] = $i;

				$correctlyReorderedPageNumbers[] = $pageNumbers;

				$arrayLength = count($pageNumbers);
				$middleIndex = floor($arrayLength / 2);
				$middleNumber = $pageNumbers[$middleIndex];
				$sum += $middleNumber;

				break;
			}
		}
	}

}








// echo json_encode($pageRules);
echo "Sum of middle page numbers [" . number_format($sum) . "], total incorrect page-number-sets[" . number_format($incorrectCount) . "], incorrect page-number-set indexes " . json_encode($incorrectIndexes) . "\nCorrected page-number-sets " . json_encode($correctlyReorderedPageNumbers) . ".";

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


function correctPageOrder(&$pageRules, &$pageNumbers)
{
	foreach($pageRules as $i => $pages)
	{
		$firstNbrIndex = array_search($pages[0], $pageNumbers);
		$secondNbrIndex = array_search($pages[1], $pageNumbers);

		$areBothPresent = ($firstNbrIndex !== false && $secondNbrIndex !== false);
		if($areBothPresent)
		{
			$isCorrectlyOrdered = ($firstNbrIndex < $secondNbrIndex);
			if(!$isCorrectlyOrdered)
			{
				// REMOVE THE NUMBER FROM ITS ORIGINAL SPOT
				array_splice($pageNumbers, $secondNbrIndex, 1);
				// PLANT THE NUMBER IMMEDIATELY AFTER THE FIRST TO FORCE THEM TO CONFORM TO THE RULE
				//  (Note that removing the 2nd number alters the index of the 1st number, hence the -1 from the offset)
				array_splice($pageNumbers, ($firstNbrIndex - 1), 1, [ $pages[0], $pages[1] ]);

				correctPageOrder($pageRules, $pageNumbers);
				break;
			}
		}
	}
}

?>
