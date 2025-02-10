<?php namespace day6;

date_default_timezone_set('America/North_Dakota/Center');

include '.\Guard.class.php';
include '.\Map.class.php';

$distinctPositionsVisited = 0;

$patrolMapFilePath = 'C:\code\advent-of-code\2024\day6\map.txt';

$map = new Map();
$map->loadFromFile($patrolMapFilePath);
$map->init();


//									828										
// echo "Obstructions[" . count($map->obstructionCoordinates) . "], " . json_encode($map->obstructionCoordinates);


$guard = new Guard();
$guard->init($map);


$forwardCount = 0;
$turnCount = 0;
while($guard->isStillWithinMap($map))
{
	if($guard->canMoveForward($map))
	{
		++$forwardCount;
		$movedToDistinctSpot = $guard->moveForward($map);
	}
	else
	{
		++$turnCount;
		echo "[{$turnCount}] " . $guard->direction[$guard->directionIndex] . " {$forwardCount} steps.  Turn Right [ {$guard->x}, {$guard->y} ].\n";
		$forwardCount = 0;

		$guard->turnRight();
	}

	if($turnCount >= 100000)
	{
		echo "\nERROR\nExceeded max safety iteration count.";
		break;
	}
}

++$turnCount;
echo "[{$turnCount}] " . $guard->direction[$guard->directionIndex] . " {$forwardCount} steps.";
echo "\nExited map.\n";

echo "Distinct positions visited [{$guard->distinctPositionsVisited}]\n";
echo "Patrol point history count [" . count($guard->patrolPointHistory) . "]\n";

?>
