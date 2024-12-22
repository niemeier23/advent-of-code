<?php namespace day2;

class LevelsReport
{
	public array $levels;
	public bool $isSafe;
	public int $fileLineNumber;
	public bool $isTrendingUpward;

	public int $unsafeIndex = -1;
	public ?int $indexRemovedToAchieveSafety = null;
	public string $unsafeReason;

	const WRONG_DIRECTION = 1;
	const TOO_FAR_APART = 2;
	const SAME_LEVEL = 3;

	public $unsafeReasons = [
		0 => 'N/A',
		1 => 'Wrong Direction',
		2 => 'Differs by too much',
		3 => 'Level is the same'
	];

	/**
	 * @var LevelsReport[] 
	 */ 
	public array $altLevelsReports;


	// ADD SOME PROPERTIES TO RECORD WHICH ALTERNATE LEVELS-REPORT WAS A SUCCESS
	//   AND/OR WHY THE 2 ALTERNATES FAILED, ALSO


	public function parseInputIntoLevels(string $line)
	{
		$this->levels = array_map('intval', explode(' ', $line));
	}

	public function determineDirectionTrend()
	{
		$upwardCount = $downwardCount = 0;

		if($this->levels !== null)
		{
			foreach($this->levels as $k => $num)
			{
				if(array_key_exists(($k + 1), $this->levels))
				{
					if($num < $this->levels[$k + 1])
					{
						++$upwardCount;
					}
					else if($num > $this->levels[$k + 1])
					{
						++$downwardCount;
					}
				}
			}

			$this->isTrendingUpward = ($upwardCount > $downwardCount);
		}
	}

	public function checkSafety()
	{
		// THIS 
		$lastLevelsIndex = (count($this->levels) - 1);

		foreach($this->levels as $k => $level)
		{
			if($k == $lastLevelsIndex) continue;


			$nextLevel = $this->levels[$k + 1];
			
			if($this->isSafeDifference($level, $nextLevel))
			{
				if($this->isTrendingUpward)
				{
					if(self::isIncreasing($level, $nextLevel))
					{
						continue;
					}
					else
					{
						$this->unsafeReason = self::WRONG_DIRECTION;
					}
				}
				else
				{
					if(self::isDecreasing($level, $nextLevel))
					{
						continue;
					}
					else
					{
						$this->unsafeReason = self::WRONG_DIRECTION;
					}
				}
			}


			$this->unsafeIndex = $k;
			$this->isSafe = false;
			return;
		}

		$this->isSafe = true;
	}


	private function isSafeDifference(int $n1, int $n2)
	{
		$distance = abs($n1 - $n2);

		if($distance >= 1)
		{
			if($distance <= 3)
			{
				return true;
			}
			else
			{
				$this->unsafeReason = self::TOO_FAR_APART;
			}
		}
		else
		{
			$this->unsafeReason = self::SAME_LEVEL;
		}
		
		return false;
	}

	private static function isIncreasing(int $n1, int $n2)
	{
		return ($n1 < $n2);
	}

	private static function isDecreasing(int $n1, int $n2)
	{
		return ($n1 > $n2);
	}


	private function copyLevels()
	{
		return array_values($this->levels);
	}


	public function createAlternateLevelsReport($altIndex)
	{
		$alt = new LevelsReport();

		$altLevels = $this->copyLevels();
		array_splice($altLevels, ($this->unsafeIndex + $altIndex), 1);
		$alt->levels = $altLevels;

		$alt->isTrendingUpward = $this->isTrendingUpward;

		$this->altLevelsReports[] = $alt;

		$this->indexRemovedToAchieveSafety = ($this->unsafeIndex + $altIndex);
	}

}