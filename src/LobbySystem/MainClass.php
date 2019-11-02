<?php

namespace LobbySystem;

use pocketmine\command\Command;
use pocketmine\command\CommandSender;
use pocketmine\command\ConsoleCommandSender;
use pocketmine\command\CommandExecutor;
use pocketmine\event\block\BlockBreakEvent;
use pocketmine\event\block\BlockPlaceEvent;
use pocketmine\event\entity\EnityDamageEvent;
use pocketmine\event\inventory\InventoryPickupItemEvent;
use pocketmine\event\player\PlayerRespawnEvent;
use pocketmine\event\player\PlayerMoveEvent;
use pocketmine\utils\Config;
use pocketmine\level\Level;
use pocketmine\event\Listener;
use pocketmine\event\player\PlayerChatEvent;
use pocketmine\event\player\PlayerDropItemEvent;
use pocketmine\event\player\PlayerExhaustEvent;
use pocketmine\event\player\PlayerInteractEvent;
use pocketmine\event\player\PlayerJoinEvent;
use pocketmine\event\player\PlayerQuitEvent;
use pocketmine\item\Item;
use pocketmine\Player;
use pocketmine\plugin\PluginBase;
use pocketmine\network\mcpe\protocol\AddEntityPacket;
use pocketmine\utils\Terminal;
use pocketmine\utils\TextFormat as T;

class MainClass extends PluginBase implements Listener{
	
    public function onEnable(){
        $this->getLogger()->info(" Enabled 	LobbySystem By LaithYoutuber ");
		@mkdir($this->getDataFolder());
		$this->saveDefaultConfig();
        $this->cfg = $this->getConfig();
		/*
			To Get Config Soon Add $this->cfg->get("");
		*/
    }

    public function onJoin(PlayerJoinEvent $playerJoinEvent){
        $player = $playerJoinEvent->getPlayer();
		$prefix = $this->cfg->get("prefix");
        $playerJoinEvent->setJoinMessage($prefix . " §c[§b+§c] §e" . $player->getName());
		$player->setAllowFlight(false);
        $this->getItems($player);
    }

    public function onQuit(PlayerQuitEvent $playerQuitEvent){
        $player = $playerQuitEvent->getPlayer();
		$prefix = $this->cfg->get("prefix");
        $playerQuitEvent->setQuitMessage($prefix . " §c[§b-§c] §e" . $player->getName());
    }

    public function getItems(Player $player){
		
		$prefix = $this->cfg->get("prefix");
        $player->getInventory()->clearAll();
        $player->getArmorInventory()->clearAll();
        $compass = Item::get(Item::COMPASS);
        $compass->setCustomName("§7Teleporter");
        $hider = Item::get(Item::SLIMEBALL);
        $hider->setCustomName("§7Player Hiden");
        $gadgets = Item::get(Item::CHEST);
        $gadgets->setCustomName("§7Gadgets");
		$profile = Item::get(Item::BLAZE_POWDER);
		$profile->setCustomName("§7Profile");
		$pc = Item::get(Item::CLAY);
		$pc->setCustomName("§7Particls {Soon}");
        $player->getInventory()->setItem(0, $hider);
		$player->getInventory()->setItem(1, $profile);
        $player->getInventory()->setItem(4, $compass);
		$player->getInventory()->setItem(7, $pc);
        $player->getInventory()->setItem(8, $gadgets);
    }
	 
    public function setCompass(PlayerInteractEvent $playerInteractEvent){
        $player = $playerInteractEvent->getPlayer();
		$server = $this->getServer();
		$prefix = $this->cfg->get("prefix");
        $item = $player->getInventory()->getItemInHand();
        if ($item->getCustomName() == "§7Teleporter"){
            $player->getInventory()->clearAll();
            $vs = Item::get(Item::DIAMOND_SWORD);
            $vs->setCustomName("§b1vs1");
			
			$sw = Item::get(Item::BLAZE_ROD);
			$sw->setCustomName("§bSkyWars");
			
			$sm = Item::get(Item::STONE);
			$sm->setCustomName("§bSkyMLG");
			
			$fa = Item::get(Item::BOW);
			$fa->setCustomName("§bFFA");
			
			$bw = Item::get(Item::BED);
			$bw->setCustomName("§bBedWars");
			
			$ex = Item::get(Item::REDSTONE);
            $ex->setCustomName("§eExit");
			
            $player->getInventory()->setItem(0, $vs);
			$player->getInventory()->setItem(1, $sw);
			$player->getInventory()->setItem(2, $sm);
			$player->getInventory()->setItem(3, $fa);
			$player->getInventory()->setItem(4, $bw);
            $player->getInventory()->setItem(8, $ex);
			
			}elseif ($item->getCustomName() == "§eExit"){
            $player->getInventory()->clearAll();
            $this->getItems($player);
			
			}elseif ($item->getCustomName() == "§bBedWars"){
            $player->getInventory()->clearAll();
            //Command Join
            $this->getServer()->dispatchCommand($player, $this->cfg->get("bedwars"));
			
			}elseif ($item->getCustomName() == "§b1vs1"){
            $player->getInventory()->clearAll();
			//Command Join
            $this->getServer()->dispatchCommand($player, $this->cfg->get("1vs1"));
			
			}elseif ($item->getCustomName() == "§bSkyWars"){
            $player->getInventory()->clearAll();
			//Command Join
            $this->getServer()->dispatchCommand($player, $this->cfg->get("skywars"));
			
			}elseif ($item->getCustomName() == "§bSkyMLG"){
            $player->getInventory()->clearAll();
			//Command Join 
            $this->getServer()->dispatchCommand($player, $this->cfg->get("skymlg"));
			
			}elseif ($item->getCustomName() == "§bFFA"){
            $player->getInventory()->clearAll();
			//Command Join
            $this->getServer()->dispatchCommand($player, $this->cfg->get("ffa"));
		}
		
		if ($item->getCustomName() == "§7Player Hiden"){
			foreach($this->getServer()->getOnlinePlayers() as $players){
                $player->hidePlayer($players);
				$player->getInventory()->setItem(0, Item::get(416, 0, 1)->setCustomName(T::AQUA . "Show Players"));
            }
		}
		if ($item->getCustomName() == T::AQUA . "Show Players"){
			foreach($this->getServer()->getOnlinePlayers() as $players){
                $player->showPlayer($players);
				$player->getInventory()->setItem(0, Item::get( Item::SLIMEBALL)->setCustomName("§7Player Hiden"));
            }
		}
		
		if ($item->getCustomName() == "§7Gadgets"){
			
			$player->getInventory()->clearAll();
			
			$g1 = Item::get(Item::NAME_TAG);
            $g1->setCustomName("§bNick");
			
			$g2 = Item::get(Item::BONE);
            $g2->setCustomName("§bSize");
			
			$g3 = Item::get(Item::FEATHER);
            $g3->setCustomName("§bColorFullArmor");
			
			$g4 = Item::get(Item::FEATHER);
            $g4->setCustomName("§bFly");
			
			$ex = Item::get(Item::REDSTONE);
            $ex->setCustomName("§eExit");
			
			$player->getInventory()->setItem(0, $g1);
			$player->getInventory()->setItem(1, $g2);
			$player->getInventory()->setItem(2, $g3);
			$player->getInventory()->setItem(3, $g4);
			$player->getInventory()->setItem(8, $ex);
		
		
	}elseif ($item->getCustomName() == "§bFly"){ 
		$player->getInventory()->clearAll();
        $this->getItems($player);
		$this->getServer()->dispatchCommand($player, "fly");
		
	}elseif ($item->getCustomName() == "§bNick"){ 
		$player->getInventory()->clearAll();
        $this->getItems($player);
		$this->getServer()->dispatchCommand($player, "nickui");
	
	}elseif ($item->getCustomName() == "§bSize"){
		$player->getInventory()->clearAll();
        $this->getItems($player);
		$this->getServer()->dispatchCommand($player, "sizeui");
		
	}elseif ($item->getCustomName() == "§bColorFullArmor"){
		$player->getInventory()->clearAll();
        $this->getItems($player);
		$this->getServer()->dispatchCommand($player, "cfa");
	}
	
	if ($item->getCustomName() == "§7Profile"){
         $this->getServer()->dispatchCommand($player, "pvpstats");
	}
	
}

    public function onCommand(CommandSender $sender, Command $command, string $label, array $args): bool{
        if ($command == "lbyoutube") {
			$prefix = $this->cfg->get("prefix");
            $sender->sendMessage($prefix . "LobbySystem By LaithYT");
            $sender->sendMessage("§n§4Youtube§r§8: LaithYoutuber");
            $sender->sendMessage("§eEnjoy");
			return true;
        }
        if ($command == "lbinfo"){
			$prefix = $this->cfg->get("prefix");
            $sender->sendMessage($prefix."LobbySystem by LaithYT");
            return true;
        }
		if ($command == "hub"){
			$sender->teleport($sender->getServer()->getDefaultLevel()->getSafeSpawn());
			$prefix = $this->cfg->get("prefix");
			$sender->sendMessage($prefix." Teleport To Hub");
			$sender->getArmorInventory()->clearAll();
			$sender->getInventory()->clearAll();
			$this->getItems($sender);
			return true;
		}
		if ($command == "lobby"){
			$prefix = $this->cfg->get("prefix");
			$sender->teleport($sender->getServer()->getDefaultLevel()->getSafeSpawn());
			$sender->sendMessage($prefix." Teleport To Lobby");
			$sender->getArmorInventory()->clearAll();
			$sender->getInventory()->clearAll();
			$this->getItems($sender);
			return true;
		}
		if ($command == "spawn"){
			$prefix = $this->cfg->get("prefix");
			$sender->teleport($sender->getServer()->getDefaultLevel()->getSafeSpawn());
			$sender->sendMessage($prefix." Teleport To Spawn");
			$sender->getArmorInventory()->clearAll();
			$sender->getInventory()->clearAll();
			$this->getItems($sender);
			return true;
		}
		if ($command->getName() === 'fly')
        if ($sender instanceof Player) {
            if ($sender->hasPermission("fly.command") or $sender->isOp()) {
                if (!$sender->getAllowFlight()) {
					$prefix = $this->cfg->get("prefix");
                    $sender->setAllowFlight(true);
                    $sender->sendMessage($prefix. "§eFly Enabled");
                    return true;
                } else {
                    if ($sender->getAllowFlight()) {
                        $sender->setAllowFlight(false);
                        $sender->sendMessage($prefix. "§4Fly Disbled");
                        return true;
                    }
                }
            } else {
                $sender->sendMessage($prefix. "You do not have a rank containing flying");
            }
        } else {
            $sender->sendMessage("Use Command In-Game :D");
            return true;
        }

    }


    public function onDrop(PlayerDropItemEvent $playerDropItemEvent){
        $player = $playerDropItemEvent->getPlayer();
		$level = $player->getLevel();
		$dlevel = $this->getServer()->getDefaultLevel();
		if($level === $dlevel){
         $playerDropItemEvent->setCancelled(true);
      }
    }


    public function onBreak(BlockBreakEvent $BlockBreakEvent){
		$player = $BlockBreakEvent->getPlayer();
		$level = $player->getLevel();
		$dlevel = $this->getServer()->getDefaultLevel();
		if($level === $dlevel){
        $BlockBreakEvent->setCancelled(true);
		}
    }

    public function onPlace(BlockPlaceEvent $blockPlaceEvent){
		$player = $blockPlaceEvent->getPlayer();
		$level = $player->getLevel();
		$dlevel = $this->getServer()->getDefaultLevel();
		if($level === $dlevel){
         $blockPlaceEvent->setCancelled(true);
      }
    }

    public function onHunger(PlayerExhaustEvent $playerExhaustEvent){
		$player = $playerExhaustEvent->getPlayer();
		$level = $player->getLevel();
		$dlevel = $this->getServer()->getDefaultLevel();
		if($level === $dlevel){
        $playerExhaustEvent->setCancelled(true);
		}
    }
	
	public function onRespawn(PlayerRespawnEvent $PlayerRespawnEvent){
     $player = $PlayerRespawnEvent->getPlayer();
     $pi = $player->getInventory();
     $pi->clearAll();
       $compass = Item::get(Item::COMPASS);
        $compass->setCustomName("§7Teleporter");
        $hider = Item::get(Item::SLIMEBALL);
        $hider->setCustomName("§7Player Hiden");
        $gadgets = Item::get(Item::CHEST);
        $gadgets->setCustomName("§7Gadgets");
		$profile = Item::get(Item::BLAZE_POWDER);
		$profile->setCustomName("§7Profile");
		$pc = Item::get(Item::CLAY);
		$pc->setCustomName("§7Particls {Soon}");
        $player->getInventory()->setItem(0, $hider);
		$player->getInventory()->setItem(1, $profile);
        $player->getInventory()->setItem(4, $compass);
		$player->getInventory()->setItem(7, $pc);
        $player->getInventory()->setItem(8, $gadgets);
	}
}
