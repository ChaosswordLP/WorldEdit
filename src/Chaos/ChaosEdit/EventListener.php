<?php
declare(strict_types = 1);

namespace Chaos\ChaosEdit;

use pocketmine\event\Listener;
use pocketmine\event\player\PlayerJumpEvent;
use pocketmine\event\player\PlayerMoveEvent;

class EventListener implements Listener {
/*
    public function onPlayerThere(PlayerJumpEvent $event): void{
        $block = $event->getPlayer()->getTargetBlock(40, [0=>true]);
        $event->getPlayer()->sendTip("$block");
    }*/
    public function onPlayerThere(PlayerMoveEvent $event): void{
        $block = $event->getPlayer()->getTargetBlock(40, [0=>true]);
        $event->getPlayer()->sendTip("$block");
    }
}