<?php namespace day7;

date_default_timezone_set('America/North_Dakota/Center');

class Equation
{
	private string $eqString;
	public int $result;
	private array $constants;
	private int $constantsCount;

	private array $operators = [ '*', '+' ];

	private bool $isValid = false;


	public function __construct(string $equationString)
	{
		$this->eqString = $equationString;
		$this->parseEquationString();
		$this->validate();
	}


	private function parseEquationString()
	{
		// e.g.
		// 1327804638093: 2 3 8 597 7 8 8 883 463
		$equationParts = explode(': ', $this->eqString);
		$this->result = intval($equationParts[0]);
		$this->constants = array_map('intval', explode(' ', $equationParts[1]));

		$this->constantsCount = count($this->constants);
	}


	private function validate()
	{
		$operatorsSequence = [];
		
		if($equation->constantsCount > 1)
		{

		}
		$i = 0;
		$operators = [];
		$runningTotal = 0;
		self::applyNextOperators($i, $equation->constantsCount, $operators);

		$this->isValid = ($runningTotal === $this->result);
	}

	private static function applyNextOperators($i, $constantsCount, $operators)
	{
		if($i < ($constantsCount - 1))
		{
			$operators_add = array_merge($operators, ['+']);
			$operators_mult = array_merge($operators, ['*']);

			++$i;
			
			self::applyNextOperators($i, $constantsCount, $operators_add);
			self::applyNextOperators($i, $constantsCount, $operators_mult);
		}
		else
		{
			$equation->operators = $operators;
			$equation->solve();
		}

		
	}


	private static function solve()
	{

	}
}
