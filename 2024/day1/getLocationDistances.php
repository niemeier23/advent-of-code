<?php

$columns = [
	0 => [],
	1 => [],
	2 => [],
	'running-distance' => 0
];

$filePath = './locationIds.txt';

$fileHandle = fopen($filePath, "r");
if($fileHandle !== false)
{
	$line = '';

	// PARSE FILE
	// STORE DATA
	while(getNextLine($fileHandle, $line)) {
        
		$lineParts = explode('   ', $line);

		$columns[0][] = intval($lineParts[0]);
		$columns[1][] = intval($lineParts[1]);
    }

	fclose($fileHandle);


	sort($columns[0]);
	sort($columns[1]);

	// CALCULATE DIFFERENCES/DISTANCES
	foreach($columns[0] as $i => $num)
	{
		$diff = abs($num - $columns[1][$i]);
		$columns[2][$i] = $diff;
		$columns['running-distance'] += $diff;
	}

	echo "Total records [" . strval($i + 1) . "]\n";
	echo "Total distance [" . strval($columns['running-distance']) . "]\n";

	writeReport($columns);
}


function getNextLine($fileHandle, &$line)
{
	$line = fgets($fileHandle);

	return ($line !== false);
}


function writeReport(&$columns)
{
	$reportFilePath = 'reports/data_' . date('His') . 'txt';
	$fileHandle = fopen($reportFilePath, 'w');
	if($fileHandle !== false)
	{
		$runningDistance = 0;

		foreach($columns[0] as $i => $num)
		{
			$runningDistance += $columns[2][$i];
			fwrite(
				$fileHandle,
				sprintf("%d\t\t%d\t\t%d\t\t%d\n", $num, $columns[1][$i], $columns[2][$i], $runningDistance)
			);
		}


		fclose($fileHandle);
	}
}

?>
