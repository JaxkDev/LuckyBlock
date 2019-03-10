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

use Jack\LuckyBlock\events;

class main extends PluginBase implements Listener{
	
	public function debug($msg){
		if($this->cfg->get('debug') == true){
			$this->getLogger()->info($msg);
			return true;
		}
		return false;
	}
		
	public function onEnable(){
		$this->prefix = C::AQUA."[".C::GOLD."LuckyBlock".C::AQUA."] ".C::RESET;
		$this->version = "1.0.1";
		$this->notes = "This build is not a release and does contain some bugs.";
        $this->saveResource("config.yml");
        $this->cfg = new Config($this->getDataFolder()."config.yml", Config::YAML, []);
		$this->debug('Config Loaded.');
		if($this->cfg->get("economy") == true){
			$this->debug("Looking for a Economy Plugin...");
			if($this->getServer()->getPluginManager()->getPlugin('EconomyAPI') == true){
				$this->economy = $this->getServer()->getPluginManager()->getPlugin('EconomyAPI');
				$this->debug('EconomyAPI Found and set to the Economy extension.');
			} else {
				$this->debug('Economy extension couldn\'t be loaded, No money rewards will go through.');
			}
		} else {
			$this->debug('Economy extension Disabled');
		}
		$this->getServer()->getPluginManager()->registerEvents(new events($this), $this);
		$this->getServer()->getPluginManager()->registerEvents($this, $this);
		$this->debug("Plugin Loaded !\n\n".C::GOLD."LuckyBlock Debug Info (LBDI):\n".C::AQUA."Ver: ".C::GREEN.$this->version."\n".C::AQUA."Notes: ".C::GREEN.$this->notes."\n");
	}
	
	public function onDisable(){
        $this->cfg->save(true);
		$this->debug("Config Saved !");
		$this->debug("Plugin Disabled.");
    }

	public function onCommand(CommandSender $sender, Command $cmd, string $label, array $args): bool{
		if(!isset($args[0])){
			//no args sent
			$sender->sendMessage('/luckyblock help for all the commands.');
			return true;
		}
		if(strtolower($cmd) == 'luckyblock'){
			switch(strtolower($args[0])){
				case "?":
				case "help":
					$sender->sendMessage(C::GREEN."=== HELP ===\n".C::GOLD."/lb help\n".C::GOLD."/lb credits\n".C::GOLD."/lb <enable/disabled>");
					break;

				case "credits":
				case "creds":
					$sender->sendMessage(C::GREEN."=== Credits ===\n".C::GOLD."Head-Dev: ".C::RED."Jackthehack21");
					break;

				case "on":
				case "enable":
					if($this->cfg->get('enabled') == true){
						$sender->sendMessage(C::RED."Plugin is already enabled !");
						break;
					}
					$this->cfg->set('enabled', true);
					$this->cfg->save(true);
					$sender->sendMessage(C::GREEN.'Plugin Enabled !');
					break;

				case "off":
				case "disable":
					if($this->cfg->get('enabled') == false){
						$sender->sendMessage(C::RED."Plugin is already disabled !");
						break;
					}
					$this->cfg->set('enabled', false);
					$this->cfg->save(true);
					$sender->sendMessage(C::GREEN.'Plugin Disabled !');
					break;

				default:
					$sender->sendMessage('Not a valid command try /luckyblock help to see the full list.');
					break;
			}
		}
		return true;
	}
}
