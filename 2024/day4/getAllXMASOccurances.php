<?php namespace day4;

date_default_timezone_set('America/North_Dakota/Center');

include 'WordSearch.class.php';

$dataFilePath = '.\xmas-search.txt';

$reportFilePath = 'reports/day4_ceres_search_' . date('Ymd_His') . '.txt';
$fileHandle = fopen($dataFilePath, "r");
if($fileHandle !== false)
{
	$line = '';
	
	$word = 'XMAS';
	$puzzle = [];

	// PARSE FILE
	// STORE DATA
	while(canGetNextLine($fileHandle, $line)) {
        
		$puzzle[] = $line;

    }

	fclose($fileHandle);



	$wordSearch = new WordSearch($puzzle, $word);

	// ALL 8 directions
	$totalWordCount = $wordSearch->getTotalWordCount();


	echo "Total intances of '{$word}' in puzzle [{$totalWordCount}]";

	$wordSearch->writeReport($reportFilePath);
}
else
{
	echo "Error reading \"corrupted memory\" into this program.\n";
}


echo "\n";
echo 'DONE';
echo "\n";



function canGetNextLine($fileHandle, &$line)
{
	$line = fgets($fileHandle);

	return ($line !== false);
}


function searchAroundFor_XMAS($x, $y)
{
	checkUpwardDirection($x, $y);
}


?>
