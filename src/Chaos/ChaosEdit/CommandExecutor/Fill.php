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

class Fill implements CommandExecutor {

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
					for($y = $minY; $y <= $maxY; $y++){
						for($z = $minZ; $z <= $maxZ; $z++){
							$editHistory->setBlockHistory($x, $y, $z, $world->getBlockAt($x, $y, $z, true, false));
							$world->setBlockAt($x, $y, $z, $blockNew, false);
						}
					}
				}
				$blockAmount = ($maxX - $minX + 1) * ($maxY - $minY + 1) * ($maxZ - $minZ + 1);
				$sender->sendMessage("§d$blockAmount §ablocks were set successfully!");
			}
		}else{
			$sender->sendMessage("§cFuck off");
		}
		return true;
	}
}
