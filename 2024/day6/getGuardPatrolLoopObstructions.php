<?php namespace day6;

date_default_timezone_set('America/North_Dakota/Center');

include '.\Guard.class.php';
include '.\Map.class.php';

$patrolMapFilePath = 'C:\code\advent-of-code\2024\day6\map.txt';

$map = new Map();
$map->loadFromFile($patrolMapFilePath);
$map->init();



$guard = new Guard();
$guard->init($map);

$forwardCount = 0;
$turnCount = 0;
$patrolSteps = 0;
while($guard->isStillWithinMap($map))
{
	if($guard->canMoveForward($map))
	{
		++$patrolSteps;
		++$forwardCount;
		$movedToDistinctSpot = $guard->moveForward($map, $forwardCount);
	}
	else
	{
		++$turnCount;
		$forwardCount = 0;

		$guard->turnRight();
	}

	if($turnCount >= 100000)
	{
		echo "\nERROR\nExceeded iteration limit.";
		break;
	}
}
++$turnCount;

echo "\nExited map in [" . number_format($patrolSteps) . "] steps.\n";


echo "Beginning to add single patrol route obstructions to test if it results in a route loop.\n";

$i = 0;

foreach($guard->patrolPointHistory as $patrolPoint)
{
	if($patrolPoint['isPotentialLoopObstruction'])
	{
		$map->setPotentialLoopingObstructionPoint($patrolPoint);
		
		$guard->reset();
		
		$forwardCount = 0;
		$turnCount = 0;
		while($guard->isStillWithinMap($map))
		{
			if($guard->canMoveForward($map))
			{
				++$forwardCount;
				$movedToDistinctSpot = $guard->moveForward($map, $forwardCount);
			}
			else
			{




				/*
				Running `php -f .\getGuardPatrolLoopObstructions.php`

				WHY DO THE COORDINATES SKIP FROM { x:72, y:36 }
				TO { x:85, y:25 } ??

				*/



				if($guard->detectedLoopingPatrolRoute)
				{
					$x = $map->potentialLoopObstructionPoint['x'];
					$y = $map->potentialLoopObstructionPoint['y'];
					
					++$i;
					
					$map->saveLoopCausingObstruction();

					$guard->isLooped = true;

					echo "[{$map->loopCausingObstructionsCount}] Detected loop-causing obstruction point({ x:{$x}, y:{$y} }).\n";
					break;
				}

				++$turnCount;
				$forwardCount = 0;

				$guard->turnRight();
			}

			if($turnCount >= 100000)
			{
				echo "\nERROR\nExceeded iteration limit.";
				break;
			}
		}

		if(!$guard->isLooped)
		{
			$x = $map->potentialLoopObstructionPoint['x'];
			$y = $map->potentialLoopObstructionPoint['y'];

			echo "Obstruction point({ x:{$x}, y:{$y} }) didn't cause loop.\n";
		}
	}

	/*
	if($i > 15)
	{
		break;
	}
	*/
}


echo "Found [" . number_format($map->loopCausingObstructionsCount) . "] loop-causing obstructions to potentially use.\n";

echo json_encode(array_slice($guard->patrolPointHistory, 0, 30), 128);

// ATTEMPT #1: 1,897  (too few)

?>
