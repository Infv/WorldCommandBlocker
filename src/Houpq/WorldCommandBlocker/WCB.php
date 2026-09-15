<?php

namespace Houpq\WorldCommandBlocker;

use pocketmine\Server;
use pocketmine\player\Player;

use pocketmine\plugin\PluginBase;

use pocketmine\utils\Config;
use pocketmine\utils\TextFormat;

use Houpq\WorldCommandBlocker\Event;
use Houpq\WorldCommandBlocker\utils\PluginUtils;

class WCB extends PluginBase{

    /** @var Config $config */
    public Config $config;
    /** @var array $blocker */
    public array $blocker = [];
    /** @var WCB $instance */
    private static WCB $instance;
    # CheckConfig
    private const CONFIG_VERSION = "2.0.0";

    /**
     * @return void
     */
    public function onLoad(): void{
        self::$instance = $this;
        $this->loadFiles();
    }
    
    /**
     * @return void
     */
    public function onEnable(): void{
        $this->loadCheck();
        $this->loadEvents();
    }
    
    /**
     * @return void
     */
    private function loadFiles(): void{
         $this->saveResource("config.yml");
         $this->config = new Config($this->getDataFolder() . "config.yml");
         $this->blocker = $this->config->getNested("Settings.blocked-commands", []);
    }

    /**
     * @return void
     */
    private function loadCheck(): void{
        # CONFIG
        if((!$this->config->exists("config-version")) || ($this->config->get("config-version") != self::CONFIG_VERSION)){
            rename($this->getDataFolder() . "config.yml", $this->getDataFolder() . "config_old.yml");
            $this->saveResource("config.yml");
            $this->getLogger()->critical("Your configuration file is outdated.");
            $this->getLogger()->notice("Your old configuration has been saved as config_old.yml and a new configuration file has been generated. Please update accordingly.");
        }
    }

    /**
     * @return void
     */
    private function loadEvents(): void{
        Server::getInstance()->getPluginManager()->registerEvents(new Event, $this);
    }

    /**
     * @return WCB
     */
    public static function getInstance(): WCB{
        return self::$instance;
    }

    /**
     * @param Player $player
     * @param string $key
     * @return string
     */
    public static function getMessage(Player $player, string $key): string{
        return PluginUtils::codeUtil($player, self::$instance->config->getNested($key, $key));
    }

    /**
     * @return string
     */
    public static function Prefix(): string{
        return TextFormat::colorize(self::$instance->config->get("Prefix"));
    }
}
