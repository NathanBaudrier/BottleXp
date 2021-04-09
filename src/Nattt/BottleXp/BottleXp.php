<?php

namespace Nattt\BottleXp;

use pocketmine\plugin\PluginBase;
use pocketmine\utils\Config;
use Nattt\BottleXp\BottleXpEvents;
use Nattt\BottleXp\BottleXpCommands;
use Nattt\BottleXp\XpCommands;

class BottleXp extends PluginBase {

    public $config;

    public function onEnable() : void
    {
        $this->getServer()->getLogger()->info($this->getName() . " : Plugin installed !");
        $this->saveDefaultConfig();
        $this->config = new Config($this->getDataFolder() . "config.yml", Config::YAML);

        $this->getServer()->getCommandMap()->register("bottlexp", new BottleXpCommands($this));
        $this->getServer()->getCommandMap()->register("experience", new XpCommands($this));
        $this->getServer()->getPluginManager()->registerEvents(new BottleXpEvents($this), $this);
    }

    public function onDisable()
    {
        $this->getServer()->getLogger()->info($this->getName() .  " : Plugin uninstalled !");
    }

}
