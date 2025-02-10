<?php namespace day6;

class Map
{
	public $point;
	public $width;
	public $height;

	public $potentialLoopObstructionPoint;

	public $loopCausingObstructions;
	public $loopCausingObstructionsCount;


	public function loadFromFile($patrolMapFilePath)
	{
		$map_fileHandle = fopen($patrolMapFilePath, "r");
		if($map_fileHandle !== false)
		{
			while($this->getNextFileLine($map_fileHandle, $line))
			{
				$this->point[] = trim($line);
			}
		
			fclose($map_fileHandle);
		}
	}


	private function getNextFileLine($fileHandle, &$line)
	{
		$line = fgets($fileHandle);

		return ($line !== false);
	}


	public function init()
	{
		$this->height = count($this->point);
		$this->width = strlen($this->point[0]);

		// $this->setObstructionCoordinates();
	}


	public function saveLoopCausingObstruction()
	{
		$this->loopCausingObstructions[] = $this->potentialLoopObstructionPoint;
		++$this->loopCausingObstructionsCount;

		$this->unsetPotentialLoopingObstructionPoint();
	}


	public function setPotentialLoopingObstructionPoint($newPoint)
	{
		if(!is_null($this->potentialLoopObstructionPoint))
		{
			$this->unsetPotentialLoopingObstructionPoint();
		}

		$this->potentialLoopObstructionPoint = $newPoint;

		$x = $this->potentialLoopObstructionPoint['x'];
		$y = $this->potentialLoopObstructionPoint['y'];
		$this->point[$y][$x] = '#';

		// echo "Testing point(\{ x:{$x}, y:{$y} \})\n";
	}

	private function unsetPotentialLoopingObstructionPoint()
	{
		$x = $this->potentialLoopObstructionPoint['x'];
		$y = $this->potentialLoopObstructionPoint['y'];
		$this->point[$y][$x] = '.';

		$this->potentialLoopObstructionPoint = null;
	}

	/*
	private function setObstructionCoordinates()
	{
		$this->obstructionCoordinates = [];

		foreach($this->point as $i => $row)
		{
			$obstructionIndex = 0;
			while($obstructionIndex !== false)
			{
				$obstructionIndex = strpos($row, '#', $obstructionIndex);
				if($obstructionIndex !== false)
				{
					$this->obstructionCoordinates[] = [ $obstructionIndex, $i ];
					++$obstructionIndex;
				}
			}
		}
	}
	*/


	public function pointIsObstruction($point)
	{
		return ($point === '#');
	}


	public function outputToFile($filePath)
	{
		$map_fileHandle = fopen($filePath, "w");
		if($map_fileHandle !== false)
		{
			foreach($this->point as $line)
			{
				fwrite($map_fileHandle, $line . "\n");
			}
		
			fclose($map_fileHandle);
		}
	}
}

?>
