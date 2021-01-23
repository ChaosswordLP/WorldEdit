<?php
declare(strict_types = 1);

namespace Chaos\ChaosEdit;

use Chaos\ChaosEdit\CommandExecutor\Fill;
use Chaos\ChaosEdit\CommandExecutor\Hollow;
use Chaos\ChaosEdit\CommandExecutor\Outline;
use Chaos\ChaosEdit\CommandExecutor\PosSelector;
use Chaos\ChaosEdit\CommandExecutor\Replace;
use Chaos\ChaosEdit\CommandExecutor\Undo;
use pocketmine\plugin\PluginBase;

class Loader extends PluginBase {

	protected function onEnable(): void{

		$posSelector = new PosSelector();
		$this->getServer()->getCommandMap()->getCommand("cpos1")->setExecutor($posSelector);
		$this->getServer()->getCommandMap()->getCommand("cpos2")->setExecutor($posSelector);
		$this->getServer()->getCommandMap()->getCommand("cfill")->setExecutor(new Fill());
		$this->getServer()->getCommandMap()->getCommand("chollow")->setExecutor(new Hollow());
		$this->getServer()->getCommandMap()->getCommand("coutline")->setExecutor(new Outline());
		$this->getServer()->getCommandMap()->getCommand("cundo")->setExecutor(new Undo());
		$this->getServer()->getCommandMap()->getCommand("creplace")->setExecutor(new Replace());

	}
}
