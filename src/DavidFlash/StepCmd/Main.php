<?php

declare(strict_types=1);

namespace DavidFlash\StepCmd;

use pocketmine\Player;
use pocketmine\Server;
use pocketmine\command\ConsoleCommandSender;
use pocketmine\command\CommandSender;
use pocketmine\command\Command;
use pocketmine\plugin\PluginBase;
use pocketmine\event\player\PlayerMoveEvent;
use pocketmine\event\entity\EntityLevelChangeEvent;
use pocketmine\block\BlockFactory;
use pocketmine\block\Block;
use pocketmine\event\Listener;
use pocketmine\level\Level;


class Main extends PluginBase implements Listener {

    public function onEnable() {
          $this->getServer()->getPluginManager()->registerEvents($this, $this);
          if(!is_file($this->getDataFolder() . "config.yml")){
			        $this->saveResource('/config.yml');
		      }
    }

    public function on_move(PlayerMoveEvent $event){
        $player = $event->getPlayer();
        $playername = $player->getName();
        $playerworld = $player->getLevel()->getName();
        $block = $player->getLevel()->getBlock($player->subtract(0, 1, 0));
      $phrase  = $this->getConfig()->getNested("command");
      $toreplace = array("{player}", "{world}");
      $replaced = array($playername, $playerworld);
      $command = str_replace($toreplace, $replaced, $phrase);

    if($this->getConfig()->getNested("multiworld-enabled")==true){
      if($player->getLevel()->getName() === $this->getConfig()->getNested("world")){
      if($block->getId()==$this->getConfig()->getNested("block-id")){
        if($player->hasPermission("stepcommand.bypass")){}
        else if($this->getConfig()->get("executor") === "console"){
          $this->getServer()->dispatchCommand(new ConsoleCommandSender, $command);
        }else{
          $this->getServer()->dispatchCommand($player, $command);
          }
        }
      }
    }else{
      if($block->getId()==$this->getConfig()->getNested("block-id")){
        if($player->hasPermission("stepcommand.bypass")){}
        else if($this->getConfig()->get("executor") === "console"){
          $this->getServer()->dispatchCommand(new ConsoleCommandSender, $command);
        }else{
          $this->getServer()->dispatchCommand($player, $command);
          }
        }
      }
    }
}
