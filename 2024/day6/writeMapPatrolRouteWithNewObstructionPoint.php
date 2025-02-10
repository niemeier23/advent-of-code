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

// PARAM1 -> x point of new obstruction
$newObstructionPoint_x = $argv[1];
$newObstructionPoint_y = $argv[2];

$map->point[$newObstructionPoint_y][$newObstructionPoint_x] = '#';


$forwardCount = 0;
$turnCount = 0;
$patrolSteps = 0;
while($guard->isStillWithinMap($map))
{
	if($guard->canMoveForward($map))
	{
		++$patrolSteps;
		++$forwardCount;
		$movedToDistinctSpot = $guard->moveForward($map, $forwardCount, $updateMap = true);
	}
	else
	{
		if($guard->detectedLoopingPatrolRoute)
		{
			echo "This obstruction point creates a looping patrol route.\n";
			break;
		}
		
		++$turnCount;
		$forwardCount = 0;

		$guard->turnRight();
		$map->point[$guard->y][$guard->x] = '+';

		if($turnCount >= 100000)
		{
			echo "\nERROR\nExceeded iteration limit.";
			break;
		}
	}

}
echo "This obstruction point didn't work.\n";

$patrolMapFilePath = 'C:\code\advent-of-code\2024\day6\mappedPatrolRoute_with_' . $newObstructionPoint_x . '.' . $newObstructionPoint_y . '_.txt';
$map->outputToFile($patrolMapFilePath);


echo "\n";
echo "See output file for patrol route map.\n";

?>
