<?php
declare(strict_types = 1);

namespace Chaos\ChaosEdit;

use pocketmine\event\Listener;
use pocketmine\event\player\PlayerMoveEvent;

class EventListener implements Listener {
    public function onPlayerThere(PlayerMoveEvent $event): void{
        $block = $event->getPlayer()->getTargetBlock(7, [0=>true]);
        $event->getPlayer()->sendTip("$block");
    }
}