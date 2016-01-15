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
        file_put_contents("ping.txt", "max-ping: 200");

      }

    }

    public function onCommand(CommandSender $sender, Command $cmd, $label, array $args) {

      if($cmd->getName() === "ping") {

        if(!(isset($args[0]))) {

          $sender->sendMessage(TF::RED . "Error: not enough args. Usage: /ping <player>");

        } else {

          $player = $this->getServer()->getPlayer($args[0]);

          if($player instanceof Player) {

            $player_name = $player->getName();
            $player_ip = $player->getAddress();
            $get_ping = 'max-ping: ';
            $config_file = file_get_contents($this->getDataFolder() . "ping.txt");
            $max_ping = substr(strstr($config_file, $get_ping), strlen($get_ping));

            $tB = microtime(true);
            $fP = fSockOpen($player_ip, 80, $errno, $errstr, 10);

            if(!($fP)) {

              $sender->sendMessage(TF::RED . $player_name . "'s IP: " . $player_ip . " was unreachable.");

            }

            $tA = microtime(true);
            $sender->sendMessage(TF::GREEN . "Pinging " . $player_ip . "...");
            $sender->sendMessage(TF::GREEN . "Result: " . round((($tA - $tB) * 1000), 0) . "ms");

          } else {

            $sender->sendMessage(TF::RED . "Error: " . $player_name . " was not found.");

          }

        }

      }

    }

    public function onJoin(PlayerJoinEvent $event) {

      $player = $event->getPlayer();
      $player_name = $player->getName();
      $player_ip = $player->getAddress();
      $get_ping = 'max-ping: ';
      $config_file = file_get_contents($this->getDataFolder() . "ping.txt");
      $max_ping = substr(strstr($config_file, $get_ping), strlen($get_ping));

      $tB = microtime(true);
      $fP = fSockOpen($player_ip, 80, $errno, $errstr, 10);

      if(!($fP)) {

        $sender->sendMessage(TF::RED . $player . "'s IP: " . $player_ip . " was unreachable.");

      }

      $tA = microtime(true);
      $ping_result = round((($tA - $tB) * 1000), 0);

      if($ping_result >= $max_ping) {

        $player->kick("Sorry, your ping is too high(too laggy), so you have been kicked.");

      }

    }

  }

?>
