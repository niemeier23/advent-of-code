<?php

$columns = [
	0 => [],
	1 => [],
	2 => []
];

$scorePoints = [];
$totalSimilarityScore = 0;

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

	$counts = array_count_values($columns[1]);

	foreach($columns[0] as $i => $num)
	{
		$occurrences = array_key_exists($num, $counts) ? $counts[$num] : 0;
		$columns[1][$i] = $occurrences;


		if(!array_key_exists($num, $scorePoints))
		{
			$scorePoints[$num] = $num * $occurrences;
		}

		$columns[2][$i] = $scorePoints[$num];

		$totalSimilarityScore += $columns[2][$i];

	}

	echo "Total records [" . strval($i + 1) . "]\n";
	echo "Similarity Score [" . strval($totalSimilarityScore) . "]\n";

	writeReport($columns);
}


function getNextLine($fileHandle, &$line)
{
	$line = fgets($fileHandle);

	return ($line !== false);
}


function writeReport(&$columns)
{
	$reportFilePath = 'reports/data2_' . date('His') . 'txt';
	$fileHandle = fopen($reportFilePath, 'w');
	if($fileHandle !== false)
	{
		$totalSimilarityScore = 0;

		foreach($columns[0] as $i => $num)
		{
			$totalSimilarityScore += $columns[2][$i];
			fwrite(
				$fileHandle,
				sprintf("%d\t\t%d\t\t%d\t\t%d\n", $num, $columns[1][$i], $columns[2][$i], $totalSimilarityScore)
			);
		}


		fclose($fileHandle);
	}
}

?>
