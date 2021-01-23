<?php
declare(strict_types = 1);

namespace Chaos\ChaosEdit\CommandExecutor;

use Chaos\ChaosEdit\CommandExecutor\PosSelector;
use Chaos\ChaosEdit\CommandExecutor\Undo;
use Chaos\ChaosEdit\EditHistory;
use InvalidArgumentException;
use pocketmine\block\Block;
use pocketmine\command\CommandExecutor;
use pocketmine\command\CommandSender;
use pocketmine\command\Command;
use pocketmine\item\ItemBlock;
use pocketmine\item\LegacyStringToItemParser;
use pocketmine\player\Player;
use pocketmine\world\Position;

class Hollow implements CommandExecutor {

	public function onCommand(CommandSender $sender, Command $command, string $label, array $args): bool{

		if($sender instanceof Player){
			if(PosSelector::$pos1 === null or PosSelector::$pos2 === null){
				$sender->sendMessage("§cYou must set all positions!");
			}elseif(count($args) === 0) {
				$sender->sendMessage("§cBlock is missing!");
			}elseif(PosSelector::$pos1->getWorld()->getFolderName() !== PosSelector::$pos1->getWorld()->getFolderName()){
				$sender->sendMessage("§cYou must stay in one World!");
			}else{
				try{
					$item = LegacyStringToItemParser::getInstance()->parse($args[0]);
				}catch(InvalidArgumentException $e){
					$sender->sendMessage("§cThat's not a valid block!");
					return true;
				}
				if(!($item instanceof ItemBlock)){
					$sender->sendMessage("§cThat's not a valid block!");
					return true;
				}
				$maxX = max(PosSelector::$pos1->x, PosSelector::$pos2->x);
				$minX = min(PosSelector::$pos1->x, PosSelector::$pos2->x);
				$maxY = max(PosSelector::$pos1->y, PosSelector::$pos2->y);
				$minY = min(PosSelector::$pos1->y, PosSelector::$pos2->y);
				$maxZ = max(PosSelector::$pos1->z, PosSelector::$pos2->z);
				$minZ = min(PosSelector::$pos1->z, PosSelector::$pos2->z);
				$blockNew = $item->getBlock();
				$world = PosSelector::$pos1->getWorld();
				Undo::$positions[] = $editHistory = new EditHistory($world->getFolderName());

				for($x = $minX; $x <= $maxX; $x++){
					for($z = $minZ; $z <= $maxZ; $z++){
						$editHistory->setBlockHistory($x, $minY, $z, $world->getBlockAt($x, $minY, $z, true, false));
						$editHistory->setBlockHistory($x, $maxY, $z, $world->getBlockAt($x, $maxY, $z, true, false));
						$world->setBlockAt($x, $minY, $z, $blockNew, false);
						$world->setBlockAt($x, $maxY, $z, $blockNew, false);
					}
				}
				for($y = $minY + 1; $y <= $maxY - 1; $y++){
					for($z = $minZ + 1; $z <= $maxZ - 1; $z++){
						$editHistory->setBlockHistory($minX, $y, $z, $world->getBlockAt($minX, $y, $z, true, false));
						$editHistory->setBlockHistory($maxX, $y, $z, $world->getBlockAt($maxX, $y, $z, true, false));
						$world->setBlockAt($minX, $y, $z, $blockNew, false);
						$world->setBlockAt($maxX, $y, $z, $blockNew, false);
					}
				}
				for($x = $minX; $x <= $maxX; $x++){
					for($y = $minY + 1; $y <= $maxY - 1; $y++){
						$editHistory->setBlockHistory($x, $y, $minZ, $world->getBlockAt($x, $y, $minZ, true, false));
						$editHistory->setBlockHistory($x, $y, $maxZ, $world->getBlockAt($x, $y, $maxZ, true, false));
						$world->setBlockAt($x, $y, $minZ, $blockNew, false);
						$world->setBlockAt($x, $y, $maxZ, $blockNew, false);
					}
				}

				$dX = $maxX - $minX;
				$dY = $maxY - $minY;
				$dZ = $maxZ - $minZ;
				$blockAmount = 2 * ($dX * $dY + $dX * $dZ + $dY * $dZ) + 2;
				$sender->sendMessage("§d$blockAmount §ablocks were set successfully!");
			}
		}else{
			$sender->sendMessage("§cFuck off");
		}
		return true;
	}
}
