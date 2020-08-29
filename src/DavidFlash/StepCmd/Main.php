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
use pocketmine\block\BlockFactory;
use pocketmine\block\Block;
use pocketmine\event\Listener;


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
        $block = $player->getLevel()->getBlock($player->subtract(0, 1, 0));

      if($block->getId()==$this->getConfig()->getNested("block-id")){
        if($player->hasPermission("stepcommand.bypass")){}
        else if($this->getConfig()->get("executor") === "console"){
          $this->getServer()->dispatchCommand(new ConsoleCommandSender, $this->getConfig()->getNested("command"));
        }else{
          $this->getServer()->dispatchCommand($player, $this->getConfig()->getNested("command"));
        }
      }
    }
}
