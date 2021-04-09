<?php

namespace Nattt\BottleXp;

use pocketmine\command\Command;
use pocketmine\command\CommandSender;
use pocketmine\Player;
use pocketmine\Server;

class XpCommands extends Command {

    private $plugin;

    public function __construct(BottleXp $plugin)
    {
        $this->plugin = $plugin;
        parent::__construct("experience", $this->plugin->config->get("XpDescription"), "/xp [add|remove|set|get] (int: experience) (player)", ["xp"]);
    }

    public function execute(CommandSender $player, string $commandLabel, array $args)
    {
        $permission = $this->plugin->config->get("XpCommands");
        if(!isset($permission)) return $this->plugin->config->set("XpCommands", "op");
        if($permission === "op") {
            if(!$player->isOp()) return $player->sendMessage($this->plugin->config->get("NoOpPermissions"));
        }else{
            if(!$player->hasPermission($permission)) return $player->sendMessage($this->plugin->config->get("NoPermissions"));
        }
        if(!isset($args[0])) return $player->sendMessage(str_replace("{usage-message}", $this->usageMessage, $this->plugin->config->get("Usage")));
        switch ($args[0]) {

            case "add":
                if(!isset($args[1])) return $player->sendMessage(str_replace("{usage-message}", $this->usageMessage, $this->plugin->config->get("Usage")));
                if(!is_numeric($args[1])) return $player->sendMessage($this->plugin->config->get("MustNumber"));
                if(!isset($args[2])) {
                    if(!$player instanceof Player) return $player->sendMessage($this->plugin->config->get("GameOnly"));
                    $player->sendMessage(str_replace("{level-add}", $args[1], $this->plugin->config->get("LevelAddedToHimself")));
                    $player->addXpLevels($args[1], true);
                    break;
                }elseif(!$user = Server::getInstance()->getPlayer($args[2])) {
                    $player->sendMessage($this->plugin->config->get("PlayerNotFound"));
                    break;
                }else{
                    $player->sendMessage(str_replace(["{level-add}", "{player-name}"], [$args[1], $user->getName()], $this->plugin->config->get("LevelAdded")));
                    $user->addXpLevels($args[1], true);
                    break;
                }

            case "remove":
                if(!isset($args[1])) return $player->sendMessage(str_replace("{usage-message}", $this->usageMessage, $this->plugin->config->get("Usage")));
                if(!is_numeric($args[1])) return $player->sendMessage($this->plugin->config->get("MustNumber"));
                if(!isset($args[2])) {
                    if(!$player instanceof Player) return $player->sendMessage($this->plugin->config->get("GameOnly"));
                    if($args[1] > $player->getXpLevel()) return $player->sendMessage($this->plugin->config->get("NumberHigher"));
                    $player->sendMessage(str_replace("{level-remove}", $args[1], $this->plugin->config->get("LevelRemovedToHimself")));
                    $player->setXpLevel($player->getXpLevel() - $args[1]);
                    break;
                }elseif(!$user = Server::getInstance()->getPlayer($args[2])) {
                    $player->sendMessage($this->plugin->config->get("PlayerNotFound"));
                    break;
                }else{
                    $player->sendMessage(str_replace(["{level-remove}", "{player-name}"], [$args[1], $user->getName()], $this->plugin->config->get("LevelRemoved")));
                    $user->setXpLevel($user->getXpLevel() - $args[1]);
                    break;
                }

            case "set":
                if(!isset($args[1])) return $player->sendMessage(str_replace("{usage-message}", $this->usageMessage, $this->plugin->config->get("Usage")));
                if(!is_numeric($args[1])) return $player->sendMessage($this->plugin->config->get("MustNumber"));
                if(!isset($args[2])) {
                    if(!$player instanceof Player) return $player->sendMessage($this->plugin->config->get("GameOnly"));
                    $player->sendMessage(str_replace("{level-set}", $args[1], $this->plugin->config->get("LevelDefinedToHimself")));
                    $player->setXpLevel($args[1]);
                    break;
                }elseif(!$user = Server::getInstance()->getPlayer($args[2])) {
                    $player->sendMessage($this->plugin->config->get("PlayerNotFound"));
                    break;
                }else{
                    $player->sendMessage(str_replace(["{level-set}", "{player-name}"], [$args[1], $user->getName()], $this->plugin->config->get("LevelDefined")));
                    $user->setXpLevel($args[1]);
                    break;
                }

            case "get":
                if(!isset($args[1])) return $player->sendMessage(str_replace("{level-get}", $player->getXpLevel(), $this->plugin->config->get("LevelGetedToHimself")));
                if(!$user = Server::getInstance()->getPlayer($args[1])) return $player->sendMessage($this->plugin->config->get("PlayerNotFound"));
                $player->sendMessage(str_replace(["{level-get}", "{player-name}"], [$user->getXpLevel(), $user->getName()], $this->plugin->config->get("LevelGeted")));
                break;

            default:
                $player->sendMessage(str_replace("{usage-message}", $this->usageMessage, $this->plugin->config->get("Usage")));
                break;
        }
    }
}
