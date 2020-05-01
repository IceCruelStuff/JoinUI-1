<?php

declare(strict_types=1);

namespace brokiem\JoinUI;

use pocketmine\event\player\PlayerJoinEvent;
use pocketmine\Server;
use pocketmine\Player;
use pocketmine\plugin\PluginBase;
use pocketmine\utils\Config;
use pocketmine\event\Listener;

use brokiem\JoinUI\lib\FormAPI;
use brokiem\JoinUI\lib\FormAPI\{Form, SimpleForm};

class JoinUI extends PluginBase implements Listener
{
    public function onEnable() {
        $this->getServer()->getPluginManager()->registerEvents($this, $this);
        $this->saveResource("config.yml");
    }
  
    public function onJoin(PlayerJoinEvent $event) {
        if ($this->getConfig()->get("enable-joinui") == "true") {
            $player = $event->getPlayer();
            if ($player instanceof Player) {
                $this->openUI($player);
            }
        }
        
        if($this->getConfig()->get("enable-joinui") == "false") {
            $player = $event->getPlayer();
            $player->sendMessage($this->getConfig()->get("joinui-off-mesaage"));
        }
    }
    
    public function openUI($player) {
        $form = new SimpleForm(function (Player $player, $data) {
            $result = $data;
            if ($result === null) {
                return true;
            }
            switch ($result) {
                case 0:
                $command = $this->getConfig()->get("button-1-command");
                $this->getServer()->getCommandMap()->dispatch($player,$command);
                    break;
                case 1:
                $command = $this->getConfig()->get("button-2-command");
                $this->getServer()->getCommandMap()->dispatch($player,$command);
                    break;
                case 2:
                    break;
            }
        });
        
        $form->setTitle($this->getConfig()->get("joinui-title"));
        $form->setContent(str_replace(["{player}", "&"], [$player->getName(), "§"], $this->getConfig()->get("joinui-text")));
        $form->addButton($this->getConfig()->get("button-1-text"));
        $form->addButton($this->getConfig()->get("button-2-text"));
        $form->addButton($this->getConfig()->get("joinui-button"));
        $form->sendToPlayer($player);
        return true;
    }
}

