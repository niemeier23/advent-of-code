<?php

$levels = [];
$safeCount = 0;
$levelIsSafe = [];

$dataFilePath = './levels.txt';

$reportFilePath = 'reports/day2_levels_' . date('His') . '.txt';


$fileHandle = fopen($dataFilePath, "r");
if($fileHandle !== false)
{
	$line = '';
	$i = 0;

	// PARSE FILE
	// STORE DATA
	while(getNextLine($fileHandle, $line)) {

		$lineParts = explode(' ', $line);

		$level = array_map(function($num) {

			return intval($num);

		}, explode(' ', $line));

		$levels[] = $level;

		$levelIsSafe[$i] = isSafeLevel($level);
		if($levelIsSafe[$i])
		{
			++$safeCount;
		}

		++$i;
    }

	fclose($fileHandle);

	// print_r($levels);
	echo "Safe Levels [" . strval($safeCount) . "]\n\n";

	writeReport($reportFilePath, $levels, $levelIsSafe, $safeCount);
}

echo "\n";
echo 'DONE';
echo "\n";




function getNextLine($fileHandle, &$line)
{
	$line = fgets($fileHandle);

	return ($line !== false);
}


function isSafeLevel($level)
{
	$isUpwardTrending = ($level[0] < $level[1]);

	$levelCount = (count($level) - 1);

	for($i = 0; $i < $levelCount; ++$i)
	{
		if(isSafeDifference($level[$i], $level[$i + 1]))
		{
			if($isUpwardTrending)
			{
				if(isIncreasing($level[$i], $level[$i + 1]))
					continue;
			}
			else
			{
				if(isDecreasing($level[$i], $level[$i + 1]))
					continue;
			}
		}

		return false;
	}

	return true;
}


function isIncreasing($n1, $n2)
{
	return ($n1 < $n2);
}


function isDecreasing($n1, $n2)
{
	return ($n1 > $n2);
}

function isSafeDifference($n1, $n2)
{
	$distance = abs($n1 - $n2);
	return ($distance >= 1 && $distance <= 3);
}



function writeReport(string $reportFilePath, array &$levels, array &$levelIsSafe, int $safeCount)
{
	$fileHandle = fopen($reportFilePath, 'w');
	if($fileHandle !== false)
	{
		fwrite($fileHandle, "Safe Levels [{$safeCount}]\n");

		foreach($levels as $i => $level)
		{
			fwrite(
				$fileHandle,
				sprintf(
					"%s\t\t%s\n", 
					json_encode($level),
					$levelIsSafe[$i] ? 'SAFE' : ''
				)
			);
		}


		fclose($fileHandle);
	}
}
?>
