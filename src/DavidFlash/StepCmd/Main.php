<?php

declare(strict_types=1);

namespace DavidFlash\StepCmd;

use pocketmine\Player;
use pocketmine\command\ConsoleCommandSender;
use pocketmine\plugin\PluginBase;
use pocketmine\event\player\PlayerMoveEvent;
use pocketmine\event\Listener;


class Main extends PluginBase implements Listener {

    public function onEnable() {
          $this->getServer()->getPluginManager()->registerEvents($this, $this);
          if(!is_file($this->getDataFolder() . "config.yml")){
			        $this->saveResource('/config.yml');
		      }
    }

    public function onPlayerMovement(PlayerMoveEvent $event){
        $player = $event->getPlayer();
        $playerName = $player->getName();
        $currentLevelName = $player->getLevel()->getName();
        $currentBlock = $player->getLevel()->getBlock($player->subtract(0, 1, 0));
        $toReplace = array("{player}", "{world}");
        $replaceWith = array($playerName, $currentLevelName);
        $config = $this->getConfig()->get("commands");

        if(!in_array($currentBlock->getId(), $this->getConfig()->get("blocks"))) return;
        if($player->hasPermission("stepcommand.bypass")) return;

        if($this->getConfig()->get("multiworld-enabled")){
          if(is_array($this->getConfig()->get("enabled-worlds"))){
            if(in_array($player->getLevel()->getName(), $this->getConfig()->get("enabled-worlds"))){
              if($this->getConfig()->get("executor") === "console"){
                foreach($config as $commands){
                  $command = str_replace($toReplace, $replaceWith, $commands);
                  $this->getServer()->dispatchCommand(new ConsoleCommandSender, $command);
                }
              }else{
                foreach($config as $commands){
                  $command = str_replace($toReplace, $replaceWith, $commands);
                  $this->getServer()->dispatchCommand($player, $command);
                }
              }
            }else{
              $this->getConfig()->set("multiworld-enabled", false);
              $this->getLogger()->warning("§c[StepCommand] -> The config must be in the correct format! Please check enabled-worlds in configuration file.");
            }
          }
        }else{
          if($this->getConfig()->get("executor") === "console"){
            foreach($config as $commands){
              $command = str_replace($toReplace, $replaceWith, $commands);
              $this->getServer()->dispatchCommand(new ConsoleCommandSender, $command);
            }
          }else{
            foreach($config as $commands){
              $command = str_replace($toReplace, $replaceWith, $commands);
              $this->getServer()->dispatchCommand($player, $command);
            }
          }
        }
    }
}
