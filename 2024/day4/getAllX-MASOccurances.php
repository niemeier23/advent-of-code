<?php namespace day4;

date_default_timezone_set('America/North_Dakota/Center');

include 'Word_X_Search.class.php';

$dataFilePath = '.\xmas-search.txt';
// $dataFilePath = '.\xmas-search-test.txt';


$reportFilePath = 'reports/day4_ceres_search_' . date('Ymd_His') . '.txt';
$fileHandle = fopen($dataFilePath, "r");
if($fileHandle !== false)
{
	$line = '';
	
	$word = 'MAS';
	$puzzle = [];

	// PARSE FILE
	// STORE DATA
	while(canGetNextLine($fileHandle, $line)) {
        
		$puzzle[] = str_replace([ "\r", "\n" ], '', $line);

    }

	fclose($fileHandle);



	$wordSearch = new Word_X_Search($puzzle, $word);

	// ALL 8 directions
	$totalWordCount = $wordSearch->get_X_MASCount();


	echo "Total intances of X-MAS in puzzle [{$totalWordCount}]";

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
