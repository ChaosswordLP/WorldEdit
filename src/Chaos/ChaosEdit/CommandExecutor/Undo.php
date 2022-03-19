<?php
declare(strict_types = 1);

namespace Chaos\ChaosEdit\CommandExecutor;

use Chaos\ChaosEdit\EditHistory;
use pocketmine\command\CommandExecutor;
use pocketmine\command\CommandSender;
use pocketmine\command\Command;


class Undo implements CommandExecutor {

	public static array $positions = [];

	public function onCommand(CommandSender $sender, Command $command, string $label, array $args): bool{
		$reset = 20;
		if(isset($args[0])){
			$reset = $args[0];
			return true;
		}else{
			if(count(self::$positions) === 0){
				$sender->sendMessage("§dThere's nothing to undo");
				return true;
			}
			if(count(self::$positions) === $reset){
				array_shift(self::$positions);
			}
			$lastEditHistory = array_pop(self::$positions);
			$world = $sender->getServer()->getWorldManager()->getWorldByName($lastEditHistory->getWorldName());
			if($world === null){
				$sender->sendMessage("§cWorld is not loaded");
				return true;
			}
			foreach($lastEditHistory->yieldBlockHistory() as [$x, $y, $z, $block]){
				$world->setBlockAt($x, $y, $z, $block, false);
			}

			$sender->sendMessage("§4You overwrote history");
			return true;
		}
	}
}
