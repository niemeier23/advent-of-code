<?php namespace day7;

date_default_timezone_set('America/North_Dakota/Center');

include '.\Equation.class.php';

function processEquationsFile()
{
	$sumOfValidEquations = 0;

	$equationsFilePath = 'C:\code\advent-of-code\2024\day7\equations_input.txt';

	$maxEquationTerms = 12;
	$operatorSequences = getAllOperatorSequences([ '*', '+' ], $maxEquationTerms);


	$fileHandle = fopen($equationsFilePath, "r");
	if($fileHandle !== false)
	{
		while(getNextFileLine($fileHandle, $line))
		{
			if($line)
			{
				$equation = new Equation(trim($line));
				$equation->validate($operatorSequences);
				if($equation->isValid())
				{
					$sumOfValidEquations += $equation->result;
				}
			}
		}

		fclose($fileHandle);
	}

	return $sumOfValidEquations;

}



function getNextFileLine(resource $fileHandle, &$line)
{
	$line = fgets($fileHandle);

	return ($line !== false);
}


function addOperatorSequenceSet(int $maxEquationTerms, array $operatorValues, array &$operatorSequenceSets)
{
	$lastSequenceSetIndex = array_key_last($operatorSequenceSets);
	if(is_null($lastSequenceSetIndex))
	{
		$operatorSequenceSets[] = $operatorValues;
	}
	else
	{
		$lastSequenceSet = $operatorSequenceSets[$lastSequenceSetIndex];
		$dupSequenceSet = $lastSequenceSet;
		$newOperatorSequenceSet = [];

		$operatorSequenceSets[] = [
			...$lastSequenceSet,
			...$dupSequenceSet
		];

		

		foreach($lastSequenceSet as &$operatorSequence)
		{
			array_push($operatorSequence, $operatorVal);
		}
	 
		foreach($dupSequenceSet as &$operatorSequence)
		{
			array_push($operatorSequence, $operatorVal);
		}

		foreach($operatorValues as $operatorIndex => $operatorVal)
		{

			foreach($lastSequenceSet as &$operatorSequence)
			{
				array_push($operatorSequence, $operatorVal);
			}

			foreach($dupSequenceSet as &$operatorSequence)
			{
				array_push($operatorSequence, $operatorVal);
			}
		}
	}
	
	
}



function getAllOperatorSequences($operatorValues, $maxEquationTerms)
{
	$i = 1;
	$operatorSequenceSets = [ [ /* empty array at index: 0 */] ];

	for($i = 1; $i < $maxEquationTerms; ++$i)
	{
		addOperatorSequenceSet($maxEquationTerms, $operatorValues, $operatorSequenceSets);
	}
}



$sum = processEquationsFile();


echo "Sum of valid equations [" . number_format($sum) . "][raw: {$sum}]";

?>
