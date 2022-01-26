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
use pocketmine\item\LegacyStringToItemParserException;
use pocketmine\player\Player;
use pocketmine\world\Position;

class Outline implements CommandExecutor {

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
				}catch(LegacyStringToItemParserException $e){
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
                    $editHistory->setBlockHistory($x, $minY, $minZ, $world->getBlockAt($x, $minY, $minZ, true, false));
                    $editHistory->setBlockHistory($x, $maxY, $maxZ, $world->getBlockAt($x, $maxY, $maxZ, true, false));
                    $editHistory->setBlockHistory($x, $minY, $maxZ, $world->getBlockAt($x, $minY, $maxZ, true, false));
                    $editHistory->setBlockHistory($x, $maxY, $minZ, $world->getBlockAt($x, $maxY, $minZ, true, false));
                    $world->setBlockAt($x, $minY, $minZ, $blockNew, false);
                    $world->setBlockAt($x, $maxY, $maxZ, $blockNew, false);
                    $world->setBlockAt($x, $minY, $maxZ, $blockNew, false);
                    $world->setBlockAt($x, $maxY, $minZ, $blockNew, false);
				}
                for($z = $minZ+1; $z < $maxZ; $z++) {
                    $editHistory->setBlockHistory($minX, $minY, $z, $world->getBlockAt($minX, $minY, $z, true, false));
                    $editHistory->setBlockHistory($minX, $maxY, $z, $world->getBlockAt($minX, $maxY, $z, true, false));
                    $editHistory->setBlockHistory($maxX, $minY, $z, $world->getBlockAt($maxX, $minY, $z, true, false));
                    $editHistory->setBlockHistory($maxX, $maxY, $z, $world->getBlockAt($maxX, $maxY, $z, true, false));
                    $world->setBlockAt($minX, $minY, $z, $blockNew, false);
                    $world->setBlockAt($minX, $maxY, $z, $blockNew, false);
                    $world->setBlockAt($maxX, $minY, $z, $blockNew, false);
                    $world->setBlockAt($maxX, $maxY, $z, $blockNew, false);
                }

                for($y = $minY+1; $y < $maxY; $y++) {
                    $editHistory->setBlockHistory($minX, $y, $minZ, $world->getBlockAt($minX, $y, $minZ, true, false));
                    $editHistory->setBlockHistory($minX, $y, $maxZ, $world->getBlockAt($minX, $y, $maxZ, true, false));
                    $editHistory->setBlockHistory($maxX, $y, $minZ, $world->getBlockAt($maxX, $y, $minZ, true, false));
                    $editHistory->setBlockHistory($maxX, $y, $maxZ, $world->getBlockAt($maxX, $y, $maxZ, true, false));
                    $world->setBlockAt($minX, $y, $minZ, $blockNew, false);
                    $world->setBlockAt($minX, $y, $maxZ, $blockNew, false);
                    $world->setBlockAt($maxX, $y, $minZ, $blockNew, false);
                    $world->setBlockAt($maxX, $y, $maxZ, $blockNew, false);
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
