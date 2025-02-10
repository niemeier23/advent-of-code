<?php namespace day6;

class Guard
{
	public $x;
	public $y;

	public $directionIndex;
	public $direction = [
		'UP',
		'RIGHT',
		'DOWN',
		'LEFT'
	];

	public $patrolPointHistory;
	public $obstructionHitHistory;
	public $distinctPositionsVisited;

	private $initialPosition;

	public $detectedLoopingPatrolRoute = false;
	public $isLooped = false;

	public function init(Map $map)
	{
		$this->directionIndex = 0;
		$this->obstructionHitHistory = [];

		foreach($map->point as $i => $row)
		{
			$guardIndex = strpos($row, '^');
			if($guardIndex !== false)
			{
				$this->x = $guardIndex;
				$this->y = $i;
				$this->initialPosition = [
					'x' => $this->x,
					'y' => $this->y,
					'isPotentialLoopObstruction' => false
				];
				$this->patrolPointHistory[] = $this->initialPosition;
				$this->distinctPositionsVisited = 1;
				break;
			}
		}
	}

	public function reset()
	{
		$this->directionIndex = 0;
		$this->obstructionHitHistory = [];

		$this->x = $this->initialPosition['x'];
		$this->y = $this->initialPosition['y'];
		$this->patrolPointHistory = [ $this->initialPosition ];
		$this->distinctPositionsVisited = 1;
		$this->detectedLoopingPatrolRoute = false;
	}


	public function canMoveForward(Map $map)
	{
		$nextCoord = [
			'x' => $this->x,
			'y' => $this->y
		];
	
		switch($this->getDirection())
		{
			case 'UP':
				--$nextCoord['y'];
				break;
	
			case 'RIGHT':
				++$nextCoord['x'];
				break;
	
			case 'DOWN':
				++$nextCoord['y'];
				break;
	
			case 'LEFT':
				--$nextCoord['x'];
				break;
		}

		
		if(!$this->isHypotheticallyStillWithinMap($nextCoord, $map))
		{
			return true;
		}
		else if(in_array($nextCoord, $this->patrolPointHistory))
		{
			return true;
		}

		$mapPoint = $map->point[$nextCoord['y']][$nextCoord['x']];

		if($map->pointIsObstruction($mapPoint))
		{
			$nextCoord['hitDirection'] = $this->getOppositeDirection();

			// IF THE GUARD IS HITTING THE SAME OBSTRUCTION FROM THE SAME DIRECTION, IT IS A LOOPING PATROL ROUTE
			$this->detectedLoopingPatrolRoute = in_array($nextCoord, $this->obstructionHitHistory);
			$this->obstructionHitHistory[] = $nextCoord;

		}
		
		return ($map->point[$nextCoord['y']][$nextCoord['x']] !== '#');
	}


	public function moveForward(Map $map, int $moveCount, $updateMap = false)
	{
		switch($this->getDirection())
		{
			case 'UP':
				--$this->y;
				$isStillWithinMap = $this->isStillWithinMap($map);
				if($updateMap)
				{
					if($isStillWithinMap)
					{
						if($map->point[$this->y][$this->x] === '-')
						{
							$map->point[$this->y][$this->x] = '+';
						}
						else
						{
							$map->point[$this->y][$this->x] = '|';
						}
					}
				}
				break;
	
			case 'RIGHT':
				++$this->x;
				$isStillWithinMap = $this->isStillWithinMap($map);
				if($updateMap)
				{
					if($isStillWithinMap)
					{
						if($map->point[$this->y][$this->x] === '|')
						{
							$map->point[$this->y][$this->x] = '+';
						}
						else
						{
							$map->point[$this->y][$this->x] = '-';
						}
					}
				}
				break;
	
			case 'DOWN':
				++$this->y;
				$isStillWithinMap = $this->isStillWithinMap($map);
				if($updateMap)
				{
					if($isStillWithinMap)
					{
						if($map->point[$this->y][$this->x] === '-')
						{
							$map->point[$this->y][$this->x] = '+';
						}
						else
						{
							$map->point[$this->y][$this->x] = '|';
						}
					}
				}
				break;
	
			case 'LEFT':
				--$this->x;
				$isStillWithinMap = $this->isStillWithinMap($map);
				if($updateMap)
				{
					if($isStillWithinMap)
					{
						if($map->point[$this->y][$this->x] === '|')
						{
							$map->point[$this->y][$this->x] = '+';
						}
						else
						{
							$map->point[$this->y][$this->x] = '-';
						}
					}
				}
				break;

			default:
				$isStillWithinMap = false;
		}

		$newCoord = [
			'x' => $this->x,
			'y' => $this->y,
			'isPotentialLoopObstruction' => true // ($moveCount > 1)
		];

		if($isStillWithinMap && !in_array($newCoord, $this->patrolPointHistory))
		{
			$this->patrolPointHistory[] = $newCoord;

			++$this->distinctPositionsVisited;

			return true;
		}
		
		return false;
	}

	public function turnRight()
	{
		// WHEN THE DIRECTION-INDEX EXCEEDS (3) THE UPPER LIMIT,
		//   THIS MODULUS MATH EFFECTIVELY CYCLES IT BACK TO 0
		$this->directionIndex = (++$this->directionIndex) % 4;
	}


	public function getDirection()
	{
		return $this->direction[$this->directionIndex];
	}


	private function getOppositeDirection()
	{
		$oppositeDirectionIndex = ($this->directionIndex + 2) % 4;
		return $this->direction[$oppositeDirectionIndex];
	}



	public function isStillWithinMap(Map $map)
	{
		if($this->x >= 0 && $this->x < $map->width)
		{
			return ($this->y >= 0 && $this->y < $map->height);
		}
	
		return false;
	}


	private function isHypotheticallyStillWithinMap(array $coord, Map $map)
	{
		if($coord['x'] >= 0 && $coord['x'] < $map->width)
		{
			return ($coord['y'] >= 0 && $coord['y'] < $map->height);
		}
	
		return false;
	}


}

?>
