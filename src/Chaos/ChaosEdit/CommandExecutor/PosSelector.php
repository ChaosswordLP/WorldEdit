<?php
declare(strict_types = 1);

namespace Chaos\ChaosEdit\CommandExecutor;

use pocketmine\command\CommandExecutor;
use pocketmine\command\CommandSender;
use pocketmine\command\Command;
use pocketmine\player\Player;
use pocketmine\world\Position;

class PosSelector implements CommandExecutor {

	public static ?Position $pos1 = null;
	public static ?Position $pos2 = null;

	public function onCommand(CommandSender $sender, Command $command, string $label, array $args): bool{
		if($sender instanceof Player){
			if(count($args) !== 0) {
				$pos = $sender->getPosition();
				$pos->x = $pos->getFloorX();
				$pos->y = $pos->getFloorY();
				$pos->z = $pos->getFloorZ();
				if(isset($args[0])){
					if($args[0] !== "~"){
							$pos->x = (int) $args[0];
					}
				}
				if(isset($args[1])){
					if($args[1] !== "~") {
							$pos->y = (int) $args[1];
					}
				}
				if(isset($args[2])){
					if($args[2] !== "~"){
							$pos->z = (int) $args[2];
					}
				}
				if($pos->getWorld()->isInWorld($pos->x, $pos->y, $pos->z)){
					if($command->getName() === "cpos1"){
						self::$pos1 = $pos;
						$sender->sendMessage("§aPosition 1 Marked");
					}elseif($command->getName() === "cpos2"){
						self::$pos2 = $pos;
						$sender->sendMessage("§aPosition 2 Marked");
					}
				}
				return true;
			}else{
				$pos = $sender->getPosition();
				$pos->x = $pos->getFloorX();
				$pos->y = $pos->getFloorY();
				$pos->z = $pos->getFloorZ();
				if($pos->getWorld()->isInWorld($pos->x, $pos->y, $pos->z)){
					if($command->getName() === "cpos1"){
						self::$pos1 = $pos;
						$sender->sendMessage("§aPosition 1 Marked at §d$pos->x, $pos->y, $pos->z");
					}elseif($command->getName() === "cpos2"){
						self::$pos2 = $pos;
						$sender->sendMessage("§aPosition 2 Marked at §d$pos->x, $pos->y, $pos->z");
					}
				}else{
					$sender->sendMessage("§cThis position is not valid");
				}
			}
		}else{
			$sender->sendMessage("§cFuck off");
		}
		return true;
	}
}
