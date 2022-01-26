<?php
declare(strict_types = 1);

namespace Chaos\ChaosEdit\CommandExecutor;

use pocketmine\command\CommandExecutor;
use pocketmine\command\CommandSender;
use pocketmine\command\Command;
use pocketmine\player\Player;


class WailaStick implements CommandExecutor {

    public function onCommand(CommandSender $sender, Command $command, string $label, array $args): bool{;
            if($sender instanceof Player){
                      $blockPos = $sender->getEyePos();

                $sender->sendMessage("$blockPos");
            }else{
                $sender->sendMessage("§cFuck off");
            }
       return true;
    }
}
