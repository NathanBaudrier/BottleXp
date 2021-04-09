<?php

namespace Nattt\BottleXp;

use pocketmine\command\Command;
use pocketmine\command\CommandSender;
use pocketmine\item\Item;
use pocketmine\Player;
use Nattt\BottleXp\BottleXp;

class BottleXpCommands extends Command {

    private $plugin;

    public function __construct(BottleXp $plugin)
    {
        $this->plugin = $plugin;
        parent::__construct("bottlexp", $this->plugin->config->get("BottleXpDescription"), "/bottlexp (int: xp)", ["bxp"]);
    }

    public function execute(CommandSender $player, string $commandLabel, array $args)
    {
        $permission = $this->plugin->config->get("BottleXpCommands");
        if(!$player instanceof Player) return $player->sendMessage($this->plugin->config->get("GameOnly"));
        if(!isset($permission)) return $this->plugin->config->set("Permission", "*");
        if($permission !== "*") {
            if(!$player->hasPermission($permission)) {
                return $player->sendMessage($this->plugin->config->get("NoPermissions"));
            }
        }
        if(!isset($args[0])) return $player->sendMessage("§c§lUsage : §r§c" . $this->usageMessage);
        if(!is_numeric($args[0])) return $player->sendMessage($this->plugin->config->get("MustNumber"));
        if($player->getXpLevel() <= 0) {
            return $player->sendMessage($this->plugin->config->get("NoLevel"));
        }elseif($args[0] > $player->getXpLevel()) {
            return $player->sendMessage(str_replace(["{level-missing}", "{level-player}"], [($args[0] - $player->getXpLevel()), $player->getXpLevel()], $this->plugin->config->get("NotEnoughLevel")));
        }else if($player->getInventory()->canAddItem($bottlexp = Item::get(Item::EXPERIENCE_BOTTLE, 0, 1)->setCustomName(str_replace("{level}", $args[0], $this->plugin->config->get("BottleXpName"))))) {
            $player->sendMessage(str_replace("{level-converted}", $args[0], $this->plugin->config->get("LevelConverted")));
            $bottlexp->setDamage($args[0]);
            $bottlexp->setLore([str_replace("{level}", $args[0], $this->plugin->config->get("LoreMessage"))]);
            $player->getInventory()->addItem($bottlexp);
            $player->setXpLevel($player->getXpLevel() - $args[0]);
        }else{ $player->sendMessage($this->plugin->config->get("InventoryFull")); }
    }

}