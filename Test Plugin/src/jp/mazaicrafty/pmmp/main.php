<?php

namespace jp\mazaicrafty\pmmp;

# Base
use pocketmine\plugin\PluginBase;
use pocketmine\Server;
use pocketmine\Player;

# Events
use pocketmine\event\Listener;

# Commands
use pocketmine\command\Command;
use pocketmine\command\CommandSender;
use pocketmine\command\CommandExecutor;

# Utils
use pocketmine\utils\TextFormat as COLOR;

class main extends PluginBase implements Listener
{
    const PLUGIN_NAME = "§a[§dTestPlugin§a]§r";
    const VERSION = "0.1.2";

    public function onLoad(): void
    {
        Server::getInstance()->getLogger()->info(self::PLUGIN_NAME . COLOR::AQUA . "Enabling!");
    }
    
    public function onEnable(): void
    {
        $this->allRegisterEvents();
        $this->messageA();
    }

    public function messageA(): void
    {
        Server::getInstance()->getLogger()->info(self::PLUGIN_NAME . COLOR::YELLOW . " is Enabling!");
        Server::getInstance()->getLogger()->info(self::PLUGIN_NAME . COLOR::AQUA . " Version " . COLOR::GREEN . self::VERSION);
        Server::getInstance()->getLogger()->info(self::PLUGIN_NAME . COLOR::GRAY . " https://github.com/MazaiCrafty/AdminTools");
        Server::getInstance()->getLogger()->critical(self::PLUGIN_NAME . COLOR::WHITE . " Thank you for observing the specified license." . COLOR::BLUE . "by @MazaiCrafty");
    }


    public function onDisable(): void
    {
        Server::getInstance()->getLogger()->info(self::PLUGIN_NAME . COLOR::GRAY . "Disabling...");
    }

    public function allRegisterEvents()
    {
        Server::getInstance()->getPluginManager()->registerEvents($this, $this);
    }

    public function onCommand(CommandSender $sender, Command $command, string $label, array $params): bool
    {
        if (!$sender instanceof Player) {
            $sender->sendMessage(self::PLUGIN_NAME . COLOR::RED . " Please use this in-game.");
            return true;
        }

        switch ($command->getName()) {
            case 'test':
            $sender->sendMessage("test command");
        }

        return true;
    }

}
