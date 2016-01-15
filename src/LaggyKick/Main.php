<?php

  namespace LaggyKick;

  use pocketmine\plugin\PluginBase;
  use pocketmine\event\Listener;
  use pocketmine\event\player\PlayerJoinEvent;
  use pocketmine\utils\TextFormat as TF;
  use pocketmine\command\Command;
  use pocketmine\command\CommandSender;
  use pocketmine\command\CommandExecutor;

  class Main extends PluginBase implements Listener {

    public function onEnable() {

      $this->getServer()->getPluginManager()->registerEvents($this, $this);

      if(!(file_exists($this->getDataFolder()))) {

        @mkdir($this->getDataFolder());
        chdir($this->getDataFolder());
        touch("ping.txt"); 
        file_put_contents("ping.txt", "max-ping: ");

      }

    }

    public function onCommand(CommandSender $sender, Command $cmd, $label, array $args) {

      if($cmd->getName() === "ping") {

        if(!(isset($args[0]))) {

          $sender->sendMessage(TF::RED . "Error: not enough args. Usage: /ping <player>");

        } else {

          $player = $this->getServer()->getPlayer($args[0]);
          $player_ip = $player->getAddress();
          $max-ping = 'max-ping: ';
          $config_file = file_get_contents($this->getDataFolder() . "ping.txt");
          $max-ping = substr(strstr($config_file, $max-ping), strlen($max-ping));

          $tB = microtime(true);
          $fP = fSockOpen($player_ip, 80, $errno, $errstr, 10);

          if(!($fP)) {

            $sender->sendMessage(TF::RED . $player . "'s IP: " . $player_ip . " was unreachable.");

          }

          $tA = microtime(true);
          $sender->sendMessage(TF::GREEN . "Pinging " . $player_ip . "...");
          $sender->sendMessage(TF::GREEN . "Result: " . round((($tA - $tB) * 1000), 0) . "ms");

        }

      }

    }

    public function onJoin(PlayerJoinEvent $event) {


