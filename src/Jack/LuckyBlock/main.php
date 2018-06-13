<?php

namespace Jack\LuckyBlock;

use pocketmine\plugin\PluginBase;
use pocketmine\event\Listener;
use pocketmine\utils\TextFormat as C;
use pocketmine\Player;
use pocketmine\utils\Config;
use pocketmine\command\Command;
use pocketmine\command\CommandSender;
use pocketmine\command\ConsoleCommandSender;
use pocketmine\event\player\{PlayerJoinEvent,PlayerQuitEvent, PlayerDeathEvent, PlayerChatEvent};;

class main extends PluginBase implements Listener{
		
	public function onEnable(){
        if (!is_dir($this->getDataFolder())) {
            @mkdir($this->getDataFolder());
            //Use default, not PM.
        }
	}
	
	public function onDisable(){
        
    }
}
