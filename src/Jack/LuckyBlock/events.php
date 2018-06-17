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

    public function isExistsEntity($name): bool
    {
        $nbt = new CompoundTag("", [
            new ListTag("Pos", [
                new DoubleTag("", 0),
                new DoubleTag("", 0),
                new DoubleTag("", 0),
            ]),
            new ListTag("Rotation", [
                new FloatTag("", 0),
                new FloatTag("", 0),
            ])
        ]);
        $name = str_replace(" ", "", ucwords($name));
        $entity = Entity::createEntity($name, $this->main->getServer()->getDefaultLevel()->getChunk(0, 0, true), $nbt);
        if (!($entity instanceof Entity))
            return false;
        $entity->close();
        return true;
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
            if($this->main->cfg->get('debug') == true){
                $this->main->getLogger()->info('Got number: '.$rand);
            }
            switch($rand){
                case 1:
                case 2:
                case 3:
                    //Give them some $$$
                    $player->getLevel()->setBlock($block, new Block(Block::AIR), true, true);
                    if(isset($this->main->economy) == false) break;
                    $mon = rand($this->main->cfg->get('rewards')['money']['min'], $this->main->cfg->get('rewards')['money']['max']);
                    $this->main->economy::getInstance()->addMoney($player, $mon);
                    $player->sendMessage(C::GOLD."Hmmm,  £".$mon."Magically transferred into your bank balance !");
                    break;

                case 4:
                case 5:
                    //Give em a mob
                    $player->getLevel()->setBlock($block, new Block(Block::AIR), true, true);
                    $nbt = new CompoundTag("", [
                        new ListTag("Pos", [
                            new DoubleTag("", $block->getX()),
                            new DoubleTag("", $block->getY()),
                            new DoubleTag("", $block->getZ()),
                        ]),
                        new ListTag("Rotation", [
                            new FloatTag("", $player->getYaw()),
                            new FloatTag("", $player->getPitch()),
                        ]),
                        new StringTag("CustomName", 'Cow')
                    ]);
                    $entity = Entity::createEntity('Cow', $player->getLevel(), $nbt);
                    $entity->spawnToAll();
                    $player->sendMessage("Woah look at what appeared !");
                    break;

                case 6:
                case 7:
                    //What about a chest...
                    $player->getLevel()->setBlock($block, new Block(Block::CHEST), true, true);
                    break;

                case 8:
                case 9:
                    //Do absolute nothing, RIP
                    $player->sendMessage('Ooops must have forgot to do something for you, Oh Well !');
                    break;
                
                case 10:
                    //change block to dirt, what good exchange rate.
                    $player->getLevel()->setBlock($block, new Block(Block::DIRT), true, true);
                    break;
                    
            }
            $player->sendPopup($rand);
            //right block so lets do stuff
        }
    }
}