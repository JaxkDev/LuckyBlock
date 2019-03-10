<?php
namespace Jack\LuckyBlock;

use pocketmine\Player;
use pocketmine\Server;
use pocketmine\entity\Entity;
use pocketmine\item\Item;
use pocketmine\block\Block;
use pocketmine\utils\Config;
use pocketmine\event\Listener;
use pocketmine\command\Command;
use pocketmine\command\CommandSender;
use pocketmine\utils\TextFormat as C;
use pocketmine\nbt\NBT;
use pocketmine\nbt\tag\CompoundTag;
use pocketmine\nbt\tag\DoubleTag;
use pocketmine\nbt\tag\FloatTag;
use pocketmine\nbt\tag\IntTag;
use pocketmine\nbt\tag\ListTag;
use pocketmine\nbt\tag\StringTag;
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
        $this->main->debug($block->getId()." was broken");
        if($block->getId() == $this->main->cfg->get('block')){
            $this->main->debug($block->getId()." triggered LB");
            $player = $event->getPlayer();
            $event->setCancelled();
            $rand = rand(1, 5);
            $this->main->debug('Got number: '.$rand);
            switch($rand){
                case 1:
                case 2: //More chance
                    //Give them some $$$
                    $player->getLevel()->setBlock($block, new Block(Block::AIR), true, true);
                    if(!isset($this->main->economy)){
                        break;
                    }
                    $mon = rand($this->main->cfg->get('money_min'), $this->main->cfg->get('money_max'));
                    if($mon >= 0){
                        $this->main->economy::getInstance()->addMoney($player, $mon);
                    } else {
                        $this->main->economy::getInstance()->reduceMoney($player, $mon);
                    }
                    $player->sendMessage(C::GOLD."Hmmm,  £".$mon." Magically transferred into your bank balance !");
                    break;
                case 3:
                    //What about a chest...
                    $player->getLevel()->setBlock($block, new Block(Block::CHEST), true, true);
                    break;
                case 4:
                    //Do absolute nothing, RIP
                    $player->getLevel()->setBlock($block, new Block(Block::AIR), true, true);
                    $player->sendMessage('Ooops must have forgot to do something for you, Oh Well !');
                    break;
                case 5:
                    //change block to dirt, what good exchange rate.
                    $player->getLevel()->setBlock($block, new Block(Block::GRASS), true, true);
                    $player->sendMessage("Pleasure doing business !");
                    break;
                    
            }
        }
    }
}
