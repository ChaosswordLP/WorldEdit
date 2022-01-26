<?php
declare(strict_types = 1);

namespace Chaos\ChaosEdit;

use Chaos\ChaosEdit\CommandExecutor\Fill;
use Chaos\ChaosEdit\CommandExecutor\Hollow;
use Chaos\ChaosEdit\CommandExecutor\Outline;
use Chaos\ChaosEdit\CommandExecutor\PosSelector;
use Chaos\ChaosEdit\CommandExecutor\Replace;
use Chaos\ChaosEdit\CommandExecutor\Undo;
use Chaos\ChaosEdit\CommandExecutor\WailaStick;
use pocketmine\plugin\PluginBase;

class Loader extends PluginBase {

	protected function onEnable(): void{

		$posSelector = new PosSelector();
		$this->getServer()->getCommandMap()->getCommand("/pos1")->setExecutor($posSelector);
		$this->getServer()->getCommandMap()->getCommand("/pos2")->setExecutor($posSelector);
		$this->getServer()->getCommandMap()->getCommand("/fill")->setExecutor(new Fill());
		$this->getServer()->getCommandMap()->getCommand("/hollow")->setExecutor(new Hollow());
		$this->getServer()->getCommandMap()->getCommand("/outline")->setExecutor(new Outline());
		$this->getServer()->getCommandMap()->getCommand("/undo")->setExecutor(new Undo());
		$this->getServer()->getCommandMap()->getCommand("/replace")->setExecutor(new Replace());
        $this->getServer()->getCommandMap()->getCommand("/waila")->setExecutor(new WailaStick());

	}
}
