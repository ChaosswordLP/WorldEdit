<?php
declare(strict_types = 1);

namespace Chaos\ChaosEdit\CommandExecutor;

use pocketmine\command\CommandExecutor;
use pocketmine\command\CommandSender;
use pocketmine\command\Command;
use pocketmine\player\Player;
use pocketmine\math\VoxelRayTrace;
use pocketmine\entity\Living;


class WailaStick implements CommandExecutor {

    public function onCommand(CommandSender $sender, Command $command, string $label, array $args): bool{;
            if($sender instanceof Player){

                $block = $sender->getTargetBlock(40, [0=>true]);
                $sender->sendMessage("$block");

            }else{
                $sender->sendMessage("§cFuck off");
            }
       return true;
    }
}
