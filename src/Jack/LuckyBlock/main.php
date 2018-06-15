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

class main extends PluginBase implements Listener{
	
	public function debug($msg){
		if($this->cfg->get('debug') == true){
			$this->getLogger()->log($msg);
			return true;
		}
		return false;
	}
		
	public function onEnable(){
        if (!is_dir($this->getDataFolder())) {
            @mkdir($this->getDataFolder());
        }
		$this->build = "06A";
		$this->version = "1.0.0";
        $this->saveResource("config.yml");
        $this->saveResource("help.txt");
        $this->cfg = new Config($this->getDataFolder()."config.yml", Config::YAML, []);
		$this->debug('Config Loaded.');
		$this->debug('Plugin Loaded with 0 errors');
	}
	
	public function onDisable(){
        $this->cfg->save(true);
        $this->debug("Config Saved.");
        $this->debug("Plugin Disabled.");
    }
}
