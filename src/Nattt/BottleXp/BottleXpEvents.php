<?php

namespace Nattt\BottleXp;

use pocketmine\event\Listener;
use pocketmine\event\player\PlayerInteractEvent;
use Nattt\BottleXp\BottleXpForms;

class BottleXpEvents implements Listener {

    private $plugin;

    public function __construct(BottleXp $plugin)
    {
        $this->plugin = $plugin;
    }

    public function onInteract(PlayerInteractEvent $event)
    {
        $player = $event->getPlayer();

        if($event->getAction() === PlayerInteractEvent::RIGHT_CLICK_AIR) {
            if($event->getItem()->getId() === 384 && $event->getItem()->getCustomName() !== false && $event->getItem()->getDamage() > 0) {
                BottleXpForms::takeExperienceLevel($player, $this->plugin);
                $event->setCancelled(true);
            }
        }

    }

}
