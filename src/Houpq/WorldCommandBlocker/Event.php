<?php

namespace Houpq\WorldCommandBlocker;

use pocketmine\player\Player;

use pocketmine\event\Listener;

use pocketmine\event\server\CommandEvent;

use Houpq\WorldCommandBlocker\utils\PluginUtils;
use Houpq\WorldCommandBlocker\WCB;

class Event implements Listener{

    /**
     * @param CommandEvent $event
     * @return void
     */
    public function onPreCommand(CommandEvent $event): void{
        $player = $event->getSender();
        $messageCMD = $event->getCommand();
        $messageCMD = trim($messageCMD);
        if(!$player instanceof Player) return;
        $worldFolderName = $player->getWorld()->getFolderName();
        if(isset(WCB::getInstance()->blocker[$worldFolderName])){
            $blockedList = WCB::getInstance()->blocker[$worldFolderName];
            $command = strtolower(explode(" ", $messageCMD, 2)[0]);
            $command = str_replace('"', "", $command);
            if(in_array($command, $blockedList)){
                $permissionToCheck = "worldcommandblocker.allow.$worldFolderName.$command";
                if(!($player->hasPermission("worldcommandblocker.allow") ||
                    $player->hasPermission("worldcommandblocker.allow.$worldFolderName") || $player->hasPermission($permissionToCheck)
                )){
                    if($command === "" || strpos($command, " ") !== false){
                        if(WCB::getInstance()->config->getNested("blocked-message")){
                            $player->sendMessage(WCB::Prefix() . WCB::getMessage($player, "error-message"));
                            PluginUtils::PlaySound($player, "mob.villager.no", 1, 1);
                        }
                        $event->cancel();
                        return;
                    }
                    $event->cancel();
                    if(WCB::getInstance()->config->getNested("blocked-message")){
                        $player->sendMessage(WCB::Prefix() . WCB::getMessage($player, "error-message"));
                        PluginUtils::PlaySound($player, "mob.villager.no", 1, 1);
                    }
                }
            }
        }
    }    
}
