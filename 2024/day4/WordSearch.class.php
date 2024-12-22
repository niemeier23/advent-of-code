<?php namespace day4;

class WordSearch
{
	private const UPWARD = 0;
	private const UPWARD_RIGHT = 1;
	private const RIGHT = 2;
	private const DOWNWARD_RIGHT = 3;
	private const DOWNWARD = 4;
	private const DOWNWARD_LEFT = 5;
	private const LEFT = 6;
	private const UPWARD_LEFT = 7;


	private array $directionsMeta;
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
	public int $totalWordsFound;



	public function __construct(array $puzzle, string $wordToSearchFor)
	{
		$this->puzzle = $puzzle;
		$this->puzzleRowCount = count($this->puzzle);
		$this->puzzleLineLength = strlen($this->puzzle[0]);

		$this->wordToSearchFor = $wordToSearchFor;
		$this->wordLength = strlen($this->wordToSearchFor);

		$this->initDirectionsMeta();
	}


	private function initDirectionsMeta()
	{
		echo "\n\n\n";
		echo "Line one has [{$this->puzzleLineLength}] characters.  It does " . str_contains($this->puzzle[0], "\n") ? '' : 'NOT'. " contain the newline.\n";


		$puzzleLineLenIndex = ($this->puzzleLineLength - 1);
		$puzzleRowCountIndex = ($this->puzzleRowCount - 1);
		$wordLengthIndex = ($this->wordLength - 1);

		$this->directionsMeta  = [
			[
				'key' => self::UPWARD,
				'name' => 'UPWARD',
				'hasFailed' => false,
				'vector' => [
					'x' => 0,
					'y' => -1
				],
				'possibleRange' => [
					'x' => [ 
						0, 
						$puzzleLineLenIndex
					],
					'y' => [
						$wordLengthIndex,
						$puzzleRowCountIndex
					]
				]
			],
			[
				'key' => self::UPWARD_RIGHT,
				'name' => 'UPWARD_RIGHT',
				'hasFailed' => false,
				'vector' => [
					'x' => 1,
					'y' => -1
				],
				'possibleRange' => [
					'x' => [ 
						0, 
						($puzzleLineLenIndex - $wordLengthIndex)
					],
					'y' => [
						$wordLengthIndex,
						$puzzleRowCountIndex
					]
				]
			],
			[
				'key' => self::RIGHT,
				'name' => 'RIGHT',
				'hasFailed' => false,
				'vector' => [
					'x' => 1,
					'y' => 0
				],
				'possibleRange' => [
					'x' => [ 
						0, 
						($puzzleLineLenIndex - $wordLengthIndex)
					],
					'y' => [
						0,
						$puzzleRowCountIndex
					]
				]
			],
			[
				'key' => self::DOWNWARD_RIGHT,
				'name' => 'DOWNWARD_RIGHT',
				'hasFailed' => false,
				'vector' => [
					'x' => 1,
					'y' => 1
				],
				'possibleRange' => [
					'x' => [ 
						0, 
						($puzzleLineLenIndex - $wordLengthIndex)
					],
					'y' => [
						0,
						($puzzleRowCountIndex - $wordLengthIndex)
					]
				]
			],
			[
				'key' => self::DOWNWARD,
				'name' => 'DOWNWARD',
				'hasFailed' => false,
				'vector' => [
					'x' => 0,
					'y' => 1
				],
				'possibleRange' => [
					'x' => [ 
						0, 
						$puzzleLineLenIndex
					],
					'y' => [
						0,
						($puzzleRowCountIndex - $wordLengthIndex)
					]
				]
			],
			[
				'key' => self::DOWNWARD_LEFT,
				'name' => 'DOWNWARD_LEFT',
				'hasFailed' => false,
				'vector' => [
					'x' => -1,
					'y' => 1
				],
				'possibleRange' => [
					'x' => [ 
						$wordLengthIndex, 
						$puzzleLineLenIndex
					],
					'y' => [
						0,
						($puzzleRowCountIndex - $wordLengthIndex)
					]
				]
			],
			[
				'key' => self::LEFT,
				'name' => 'LEFT',
				'hasFailed' => false,
				'vector' => [
					'x' => -1,
					'y' => 0
				],
				'possibleRange' => [
					'x' => [ 
						$wordLengthIndex, 
						$puzzleLineLenIndex
					],
					'y' => [
						0,
						$puzzleRowCountIndex
					]
				]
			],
			[
				'key' => self::UPWARD_LEFT,
				'name' => 'UPWARD_LEFT',
				'hasFailed' => false,
				'vector' => [
					'x' => -1,
					'y' => -1
				],
				'possibleRange' => [
					'x' => [ 
						$wordLengthIndex, 
						$puzzleLineLenIndex
					],
					'y' => [
						$wordLengthIndex,
						$puzzleRowCountIndex
					]
				]
			]
		];
	}


	public function getTotalWordCount()
	{
		$this->totalWordsFound = 0;

		foreach($this->puzzle as $this->rowIndex => $this->line)
		{
			for($this->columnIndex = 0; $this->columnIndex < $this->puzzleLineLength; ++$this->columnIndex)
			{
				$char = $this->line[$this->columnIndex];
				if($char === $this->wordToSearchFor[0])
				{
					$wordsFound = $this->getWordsFoundForEachDirection();
					$this->totalWordsFound += $wordsFound;
				}
				else
				{
					$wordsFound = 0;
				}

				$this->wordSearchResults[] = [
					'x' => $this->columnIndex,
					'y' => $this->rowIndex,
					'char' => $char,
					'wordsStartingHere' => $wordsFound,
					'runningTotal' => $this->totalWordsFound, // THUS FAR
					'directions' => ($wordsFound > 0) ? array_map(function($direction) {
						
						return $direction['name'];
						
					}, array_filter($this->searchDirections, function($direction) {

						return !$direction['hasFailed'];

					})) : []
				];
				
			}

		}

		return $this->totalWordsFound;
	}
	

	private function getWordsFoundForEachDirection()
	{
		$wordsFound = 0;
		$this->searchDirections = $this->directionsMeta;
		
		foreach($this->searchDirections as $key => $direction)
		{
			if(!$direction['hasFailed'])
			{
				if($this->inPossibleIndexRange($this->columnIndex, $direction['possibleRange']['x']))
				if($this->inPossibleIndexRange($this->rowIndex, $direction['possibleRange']['y']))
				{
					// ESSENTIALLY THE RADIUS, (WORDLENGTH - 1)
					for($i = 1; $i < $this->wordLength; ++$i)
					{
						$rel_x = $this->columnIndex + ($direction['vector']['x'] * $i);
						$rel_y = $this->rowIndex + ($direction['vector']['y'] * $i);

						if(array_key_exists($rel_y, $this->puzzle))
						{
							if(isset($this->puzzle[$rel_y][$rel_x]))
							{
								$char = $this->puzzle[$rel_y][$rel_x];
								$direction['hasFailed'] = ($char !== $this->wordToSearchFor[$i]);
								if($direction['hasFailed'])
								{
									break;
								}
							}
						}
					}

					if(!$direction['hasFailed'])
					{
						++$wordsFound;
					}
				}
			}
		}

		return $wordsFound;
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
			fwrite($fileHandle, "Total intances of '{$this->wordToSearchFor}' in puzzle [{$this->totalWordsFound}]\n");
	
			foreach($this->wordSearchResults as $i => $result)
			{
				if($i % ($this->puzzleLineLength - 1) == 0)
				{
					fwrite(
						$fileHandle,
						"\n##### LINE " . ($i + 1) . " ####################################\n"
					);
				}

				fwrite(
					$fileHandle,
					"RowIndex[{$result['y']}], Char[{$result['char']}], WordCount[{$result['wordsStartingHere']}], ColumnIndex[{$result['x']}] \n"
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
