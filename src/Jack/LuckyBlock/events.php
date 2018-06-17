<?php
namespace Jack\LuckyBlock;

use pocketmine\Player;
use pocketmine\Server;
use pocketmine\item\Item;
use pocketmine\block\Block;
use pocketmine\utils\Config;
use pocketmine\event\Listener;
use pocketmine\command\Command;
use pocketmine\command\CommandSender;
use pocketmine\utils\TextFormat as C;
use pocketmine\event\block\{BlockBreakEvent};;
class events implements Listener
{
    private $main;
    public function __construct(Main $plugin)
    {
        $this->main = $plugin;
    }
    
    public function blockBreak(BlockBreakEvent $event)
    {
        if($this->main->cfg->get('enabled') != true){
            return;
        }
        $block = $event->getBlock();
        if($this->main->cfg->get('debug')){
            $this->main->getLogger()->info($block->getId()." was broken");
        }
        if($block->getId() == $this->main->cfg->get('block')){
            if($this->main->cfg->get('debug')){
                $this->main->getLogger()->info($block->getId()." triggered LB");
            }
            $player = $event->getPlayer();
            $event->setCancelled();
            $rand = rand(1, 10);
            switch($rand){
                case 1:
                case 2:
                case 3:
                    //Give them some $$$
                    break;

                case 4:
                case 5:
                    //Give em a mob
                    break;

                case 6:
                case 7:
                    //What about a chest...
                    break;

                case 8:
                case 9:
                    //Do absolute nothing, RIP
                    break;
                
                case 10:
                    //change block to dirt, what good exchange rate.
                    break;
                    
            }
            $player->sendPopup($rand);
            //right block so lets do stuff
        }
    }
}