<?php

/*
 * ___  ___               _ _____            __ _         
 * |  \/  |              (_)  __ \          / _| |        
 * | .  . | __ _ ______ _ _| /  \/_ __ __ _| |_| |_ _   _ 
 * | |\/| |/ _` |_  / _` | | |   | '__/ _` |  _| __| | | |
 * | |  | | (_| |/ / (_| | | \__/\ | | (_| | | | |_| |_| |
 * \_|  |_/\__,_/___\__,_|_|\____/_|  \__,_|_|  \__|\__, |
 *                                                   __/ |
 *                                                  |___/
 * Copyright (C) 2017-2018 @MazaiCrafty (https://twitter.com/MazaiCrafty)
 *
 * This program is free plugin.
 */

namespace jp\mazaicrafty\pmmp\NoOP_NoFly;

# Base
use pocketmine\plugin\PluginBase;
use pocketmine\Server;
use pocketmine\Player;

# Events
use pocketmine\event\Listener;
use pocketmine\event\player\PlayerToggleFlightEvent;

# Commands
use pocketmine\command\Command;
use pocketmine\command\CommandSender;
use pocketmine\command\CommandExecuter;
use pocketmine\command\ConsoleCommandSender;

# Utils
use pocketmine\utils\TextFormat as COLOR;
use pocketmine\utils\Config;

class Main extends PluginBase implements Listener{

    const PREFIX = "§a[§dNoOP NoFly§a]§r ";
    const VERSION = "1.0.3";

    public function onLoad(): void{
    }
    
    public function onEnable(): void{
        $this->allRegisterEvents();
        $this->messageA();

        $this->Config = new Config($this->getDataFolder() . "Config.yml", Config::YAML, array(
            "FLYKICK" => true,
            "BROADCAST" => "§a%PLAYER%§bから不正なFlyを検知したため、キックを実行しました。",
            "KICKMESSAGE" => "§a不正なFlyを検知したのでキックを実行しました"
        ));
    }

    public function onCommand(CommandSender $sender, Command $command, string $lavel, array $args): bool{
        if (!$sender instanceof Player){
            $sender->sendMessage(self::PREFIX . COLOR::RED . "Please use this in-game.");
            return true;
        }

        if (!$sender instanceof OP){
            $sender->sendMessage(self::PREFIX . COLOR::RED . "no op");
            return true;
        }

        switch ($command->getName()){
            case "nofly":
            if (!isset(args[0])){
                return false;
            }else{
                switch ($args[0]){
                    case "on":
                    case "true":
                    $this->Config->set("FLYKICK", true);
                    $this->Config->save();
                    $sender->sendMessage(self::PREFIX . COLOR::YELLOW . "設定を変更しました:\n" . COLOR::GREEN . "有効");
                    break;

                    case "off":
                    case "false":
                    $this->Config->set("FLYKICK", false);
                    $this->Config->save();
                    $sender->sendMessage(self::PREFIX . COLOR::YELLOW . "設定を変更しました:\n" . COLOR::RED . "無効");
                    break;

                    case "query":
                    case "config":
                    $query = $this->Config->get("FLYKICK");
                    if (!$query == "true"){
                        $sender->sendMessage(self::PREFIX . COLOR::YELLOW . "現在の設定:\n" . COLOR::RED . "無効");
                    }else{
                        $sender->sendMessage(self::PREFIX . COLOR::YELLOW . "現在の設定:\n" . COLOR::GREEN . "有効");
                    }
                }
            }
        }

        return true;
    }

    public function onSenseFly(PlayerToggleFlightEvent $event){
        $player = $event->getPlayer();
        $name = $player->getName();
        $mode = $event->isFlying();
        $players = Server::getInstance()->getOnlinePlayers();

        foreach ($players as $player){
            if (!$player instanceof OP && $player->isFlying()){
                $str = $this->Config->get("BROADCAST");
                $broadcast = str_replace("%PLAYER", $name, $str);
                Server::getInstance()->broadcastMessage(self::PREFIX . COLOR::WHITE . $str);
                $player->kick(KICKMESSAGE, false);
            }else{
                $str = $this->Config->get("BROADCAST");
                $broadcast = str_replace("%PLAYER", $name, $str);
                Server::getInstance()->broadcastMessage(self::PREFIX . COLOR::WHITE . $str);
                $player->kick(KICKMESSAGE, false);
            }

        }
    }

    public function onDisable(): void{
    }

    public function messageA(){
        Server::getInstance()->getLogger()->info(self::PREFIX . COLOR::YELLOW . "is Enabling!");
        Server::getInstance()->getLogger()->info(self::PREFIX . COLOR::AQUA . "Version " . COLOR::GREEN . self::VERSION);
        Server::getInstance()->getLogger()->info(self::PREFIX . COLOR::GRAY . "https://github.com/MazaiCrafty");
        Server::getInstance()->getLogger()->critical(self::PREFIX . COLOR::WHITE . "Thank you for observing the specified license." . COLOR::BLUE . " by @MazaiCrafty");
    }

    public function allRegisterEvents(){
        if(!file_exists($this->getDataFolder())){
            mkdir($this->getDataFolder(), 0755, true); 
            }
        Server::getInstance()->getPluginManager()->registerEvents($this, $this);
    }
}
