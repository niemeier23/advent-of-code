<?php namespace day4;

class Word_X_Search
{
	
	private array $searchDirections;


	private array $puzzle;
	private string $line;
	private int $rowIndex;
	private int $columnIndex;

	private string $wordToSearchFor;
	private string $wordLength;

	private int $puzzleLineLength;
	private int $puzzleRowCount;


	public array $wordSearchResults = [];
	public int $wordsFound;
	public int $total_X_WordsFound;


	private $wordCrossingStats = [];



	public function __construct(array $puzzle, string $wordToSearchFor)
	{
		$this->puzzle = $puzzle;
		$this->puzzleRowCount = count($this->puzzle);
		$this->puzzleLineLength = strlen($this->puzzle[0]);

		$this->wordToSearchFor = $wordToSearchFor;
		$this->wordLength = strlen($this->wordToSearchFor);

	}


	public function get_X_MASCount()
	{
		$this->total_X_WordsFound = 0;

		foreach($this->puzzle as $this->rowIndex => $this->line)
		{
			if($this->rowIndex < ($this->puzzleRowCount - 2))
			{
				echo "puzzleLineLength[{$this->puzzleLineLength}]\n";
				for($this->columnIndex = 0; $this->columnIndex < ($this->puzzleLineLength - 2); ++$this->columnIndex)
				{
					$char = $this->line[$this->columnIndex];
					if($char === $this->wordToSearchFor[0] || $char === $this->wordToSearchFor[($this->wordLength - 1)])
					{
						if($this->is_X_WordMatch())
						{
							++$this->total_X_WordsFound;
						}
					}
				}
			}
		}

		return $this->total_X_WordsFound;
	}
	

	private function is_X_WordMatch()
	{
		$wordCrossingStat = [
			'isX-MASMatch' => false,
			'initWord' => '',
			'oppositeWord' => '',
			'x' => $this->columnIndex,
			'y' => $this->rowIndex
		];

		for($i = 0, $j = 2; $i < 3; ++$i, --$j)
		{
			$wordCrossingStat['initWord'] .= $this->puzzle[($this->rowIndex + $i)][($this->columnIndex + $i)];
			$wordCrossingStat['oppositeWord'] .= $this->puzzle[($this->rowIndex + $i)][($this->columnIndex + $j)];
		}

		if($wordCrossingStat['initWord'] === 'MAS' || $wordCrossingStat['initWord'] === 'SAM')
		{
			$wordCrossingStat['isX-MASMatch'] = ($wordCrossingStat['oppositeWord'] === 'MAS' || $wordCrossingStat['oppositeWord'] === 'SAM');
		}

		$this->wordCrossingStats[] = $wordCrossingStat;

		return $wordCrossingStat['isX-MASMatch'];
	}


	private function inPossibleIndexRange(int $num, array &$possibleRange)
	{
		return ($num >= $possibleRange[0] && $num <= $possibleRange[1]);
	}


	public function writeReport(string $reportFilePath)
	{
		$fileHandle = fopen($reportFilePath, 'w');
		if($fileHandle !== false)
		{
			fwrite($fileHandle, "Total intances of '{$this->wordToSearchFor}' in puzzle [{$this->total_X_WordsFound}]\n");
	
			foreach($this->wordCrossingStats as $i => $stat)
			{
				

				fwrite(
					$fileHandle,
					"ColumnIndex[{$stat['x']}], RowIndex[{$stat['y']}], initWord[{$stat['initWord']}], oppositeWord[{$stat['oppositeWord']}], isX-MASMatch[" . ($stat['isX-MASMatch'] ? 'true' : 'false') . "]  \n"			
				);
			}
	
			fclose($fileHandle);
		}
	}


	private function inRange(int $num, array $range)
	{
		return ($num >= $range[0] && $num <= $range[1]);
	}

}

?>
