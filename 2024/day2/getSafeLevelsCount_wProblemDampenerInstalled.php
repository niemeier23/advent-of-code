<?php namespace day2;

date_default_timezone_set('America/North_Dakota/Center');

include 'levelsReport.class.php';

$reports = [];
$safeCount = 0;
$levelReportIsSafe = [];

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

		++$i;

		$levelsReport = new LevelsReport();
		$levelsReport->parseInputIntoLevels($line);
		$levelsReport->determineDirectionTrend();
		$levelsReport->fileLineNumber = $i;

		$reports[] = $levelsReport;
    }

	fclose($fileHandle);

	
	$safeCount = getSafeReportsCount($reports, $problemDampenerIsInstalled = true);
	


	// if($i >= 24) break;


	// print_r($levels);
	echo "Safe Levels [" . strval($safeCount) . " out of {$i}] (w/Installed Problem Dampener)\n\n";

	writeReport($reportFilePath, $reports, $safeCount);
}

echo "\n";
echo 'DONE';
echo "\n";




function getNextLine($fileHandle, &$line)
{
	$line = fgets($fileHandle);

	return ($line !== false);
}


function getSafeReportsCount(array $reports, bool $problemDampenerIsInstalled = false)
{
	$safeCount = 0;

	foreach($reports as $k => $levelsReport)
	{
		$levelsReport->checkSafety();
		
		if(!$levelsReport->isSafe && $problemDampenerIsInstalled)
		{
			// ELIMINATE LEFT & RIGHT LEVELS, SEPARATELY, AND CHECK
			//  IF THE REPORT IS SAFE WITHOUT EITHER.

			$levelsReport->createAlternateLevelsReport(0);
			$levelsReport->altLevelsReports[0]->checkSafety();

			if(!$levelsReport->altLevelsReports[0]->isSafe)
			{
				$levelsReport->createAlternateLevelsReport(1);
				$levelsReport->altLevelsReports[1]->checkSafety();
				$levelsReport->isSafe = $levelsReport->altLevelsReports[1]->isSafe;	
			}
		}

		if($levelsReport->isSafe || 
		   $levelsReport->altLevelsReports[0]->isSafe || 
		   $levelsReport->altLevelsReports[1]->isSafe)
		{
			++$safeCount;
		}
	}

	return $safeCount;
}


function writeReport(string $reportFilePath, array &$reports, int $safeCount)
{
	$fileHandle = fopen($reportFilePath, 'w');
	if($fileHandle !== false)
	{
		fwrite($fileHandle, "Safe Levels [{$safeCount} out of " . count($reports) . "]\n");

		foreach($reports as $i => $levelsReport)
		{
			$isProblemDampener_SAFE = (
				$levelsReport->isSafe && 
				!is_null($levelsReport->indexRemovedToAchieveSafety)
			);


			fwrite(
				$fileHandle,
				sprintf(
					"%-5s%-28s%-6s%s\n",
					strval($i), 
					json_encode($levelsReport->levels),
					$levelsReport->isSafe ? 'SAFE' : '',
					$levelsReport->isSafe ?
						'' :
						"Indexes[{$levelsReport->unsafeIndex} -> " . ($levelsReport->unsafeIndex + 1) . "] {$levelsReport->unsafeReasons[$levelsReport->unsafeReason]}"
				)
			);

			if($isProblemDampener_SAFE)
			{
				fwrite(
					$fileHandle,
					sprintf(
						"\t\t%-28s%-6s%-11s%s\n",
						json_encode($levelsReport->altLevelsReports[0]->levels),
						$levelsReport->altLevelsReports[0]->isSafe ? 'SAFE' : '',
						"Index[{$levelsReport->indexRemovedToAchieveSafety}]",
							$levelsReport->altLevelsReports[0]->isSafe ?
								"Removed to achieve safety" :
								$levelsReport->altLevelsReports[0]->unsafeReasons[$levelsReport->altLevelsReports[0]->unsafeReason]
					)
				);

				if(array_key_exists(1, $levelsReport->altLevelsReports))
				{
					fwrite(
						$fileHandle,
						sprintf(
							"\t\t%-28s%-6s%-11s%s\n",
							json_encode($levelsReport->altLevelsReports[1]->levels),
							$levelsReport->altLevelsReports[1]->isSafe ? 'SAFE' : '',
							"Index[{$levelsReport->indexRemovedToAchieveSafety}]",
							$levelsReport->altLevelsReports[1]->isSafe ?
								"Removed to achieve safety" :
								$levelsReport->altLevelsReports[1]->unsafeReasons[$levelsReport->altLevelsReports[1]->unsafeReason]
						)
					);
				}
			}
		}

		fclose($fileHandle);
	}
}


function ordinal($number)
{
    $last = substr($number, -1);
    if($last == 0 || $last > 3 || ($number >= 11 && $number <= 19))
	{
		$suffix = 'th';
    }
	else if($last == 3)
	{
		$suffix = 'rd';
    }
	else if($last == 2)
	{
		$suffix = 'nd';
    }
	else
	{
		$suffix = 'st';
    }

    return strval($number) . $suffix;
}

?>
