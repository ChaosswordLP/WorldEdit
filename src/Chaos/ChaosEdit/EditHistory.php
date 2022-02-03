<?php
declare(strict_types = 1);

namespace Chaos\ChaosEdit;

use Generator;
use pocketmine\block\Block;

class EditHistory {

	private string $worldName;
	private array $positions = [];

	public function __construct(string $worldName){
		$this->worldName = $worldName;
	}

	public function getWorldName(): string{
		return $this->worldName;
	}

	public function setBlockHistory(int $x, int $y, int $z, Block $block): void{
        if(isset($this->positions["$x;$y;$z"])){
            throw new \InvalidArgumentException("Cannot overwrite history");
        }
        $this->positions["$x;$y;$z"] = $block;
	}

	public function getBlockAmount(): int{
		return count($this->positions);
	}

	public function yieldBlockHistory(): Generator{
		foreach($this->positions as $posData => $block){
			$posData = explode(";", $posData);
			yield [
				(int) $posData[0],
				(int) $posData[1],
				(int) $posData[2],
				$block
			];
		}
	}
}
