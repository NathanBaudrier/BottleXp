<?php

namespace Nattt\BottleXp;

use Nattt\BottleXp\Api\CustomForm;
use pocketmine\item\Item;
use pocketmine\Player;
use Nattt\BottleXp\BottleXp;

class BottleXpForms {

    private static $plugin;

    public static function takeExperienceLevel(Player $player, BottleXp $plugin)
    {
        self::$plugin = $plugin;
        $ui = new CustomForm(function (Player $player, $result) {

            if(!isset($result)) return;
            if($result[1] === true) {
                $player->addXpLevels($player->getInventory()->getItemInHand()->getDamage());
                $player->sendMessage(str_replace(["{level-extracted}", "{level-player}"], [$player->getInventory()->getItemInHand()->getDamage(), $player->getXpLevel()], self::$plugin->config->get("AllLevelExtracted")));
                $player->getInventory()->clear($player->getInventory()->getHeldItemIndex());
            }elseif ($result[0] == $player->getInventory()->getItemInHand()->getDamage()) {
                $player->addXpLevels(abs($result[0]));
                $player->sendMessage(str_replace(["{level-extracted}", "{level-player}"], [$player->getInventory()->getItemInHand()->getDamage(), $player->getXpLevel()], self::$plugin->config->get("AllLevelExtracted")));
                $player->getInventory()->clear($player->getInventory()->getHeldItemIndex());
            }else{
                $player->addXpLevels($result[0]);
                $player->sendMessage(str_replace(["{level-extracted}", "{level-player}", "{level}"], [$result[0], $player->getXpLevel(), ($player->getInventory()->getItemInHand()->getDamage() - $result[0])], self::$plugin->config->get("LevelExtracted")));
                $bottlexp = Item::get(Item::EXPERIENCE_BOTTLE, 0, 1)->setCustomName(str_replace("{level}", $player->getInventory()->getItemInHand()->getDamage() - $result[0], self::$plugin->config->get("BottleXpName")));
                $bottlexp->setDamage($player->getInventory()->getItemInHand()->getDamage() - $result[0]);
                $bottlexp->setLore([str_replace("{level}", $player->getInventory()->getItemInHand()->getDamage() - $result[0], self::$plugin->config->get("LoreMessage"))]);
                $player->getInventory()->setItemInHand($bottlexp);
            }
        });

        $ui->setTitle(self::$plugin->config->get("Title"));
        $ui->addSlider(self::$plugin->config->get("Description"), 1, $player->getInventory()->getItemInHand()->getDamage());
        $ui->addToggle(str_replace("{level}", $player->getInventory()->getItemInHand()->getDamage(), self::$plugin->config->get("Button")));
        $ui->sendToPlayer($player);
    }

}