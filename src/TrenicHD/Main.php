<?php

namespace TrenicHD;


use Couchbase\PrefixSearchQuery;
use pocketmine\event\player\PlayerDeathEvent;
use CB\Inventory\Inv;
use pocketmine\event\player\PlayerJoinEvent;
use pocketmine\event\player\PlayerQuitEvent;
use Cassandra\Time;
use pocketmine\command\defaults\SetWorldSpawnCommand;
use pocketmine\item\Item;
use pocketmine\network\mcpe\protocol\SetTimePacket;
use pocketmine\Player;
use pocketmine\command\CommandSender;
use pocketmine\command\Command;
use pocketmine\plugin\PluginBase;
use pocketmine\utils\TextFormat;
use pocketmine\entity\Effect;
use pocketmine\entity\EffectInstance;
use bansystem\command\BanCommand;
use bansystem\command\BanIPCommand;
use bansystem\command\BanListCommand;
use bansystem\command\BlockCommand;
use bansystem\command\BlockIPCommand;
use bansystem\command\BlockListCommand;
use bansystem\command\KickCommand;
use bansystem\command\MuteCommand;
use bansystem\command\MuteIPCommand;
use bansystem\command\MuteListCommand;
use bansystem\command\PardonCommand;
use bansystem\command\PardonIPCommand;
use bansystem\command\TempBanCommand;
use bansystem\command\TempBanIPCommand;
use bansystem\command\TempBlockCommand;
use bansystem\command\TempBlockIPCommand;
use bansystem\command\TempMuteCommand;
use bansystem\command\TempMuteIPCommand;
use bansystem\command\UnbanCommand;
use bansystem\command\UnbanIPCommand;
use bansystem\command\UnblockCommand;
use bansystem\command\UnblockIPCommand;
use bansystem\command\UnmuteCommand;
use bansystem\command\UnmuteIPCommand;
use bansystem\listener\PlayerChatListener;
use bansystem\listener\PlayerCommandPreproccessListener;
use bansystem\listener\PlayerPreLoginListener;
use pocketmine\event\Listener;
use pocketmine\permission\Permission;
use pocketmine\plugin\Plugin;
use function pocketmine\server;
use pocketmine\utils\Config;

class Main extends PluginBase implements Listener
{

    public function onLoad()
    {
        $this->getLogger()->info("Plugin wird Geladen!");
        $this->getConfig();
        $this->getLogger()->info("Config wird erstellt/Geladen!");
        $this->saveResource("config.yml");
        $config = new Config($this->getDataFolder() . "config.yml" , Config::YAML);
        $config->save();
    }

    public function onEnable(): void
    {
        $this->getLogger()->info("Plugin wurde erfolgreich Geladen! by TrenicHD");
        $this->getLogger()->warning(TextFormat::GOLD . "###############################################################################################################");
        $this->getLogger()->warning(TextFormat::GOLD . "#                                                                                                             ");
        $this->getLogger()->warning(TextFormat::AQUA . "#                     ██████╗░██╗░░░██╗  ████████╗██████╗░███████╗███╗░░██╗██╗░█████╗░██╗░░██╗██████╗░       ");
        $this->getLogger()->warning(TextFormat::GREEN . "#                     ██╔══██╗╚██╗░██╔╝  ╚══██╔══╝██╔══██╗██╔════╝████╗░██║██║██╔══██╗██║░░██║██╔══██╗      ");
        $this->getLogger()->warning(TextFormat::AQUA . "#                     ██████╦╝░╚████╔╝░  ░░░██║░░░██████╔╝█████╗░░██╔██╗██║██║██║░░╚═╝███████║██║░░██║       ");
        $this->getLogger()->warning(TextFormat::GREEN . "#                     ██╔══██╗░░╚██╔╝░░  ░░░██║░░░██╔══██╗██╔══╝░░██║╚████║██║██║░░██╗██╔══██║██║░░██║       ");
        $this->getLogger()->warning(TextFormat::AQUA . "#                     ██████╦╝░░░██║░░░  ░░░██║░░░██║░░██║███████╗██║░╚███║██║╚█████╔╝██║░░██║██████╔╝       ");
        $this->getLogger()->warning(TextFormat::GREEN . "#                     ╚═════╝░░░░╚═╝░░░  ░░░╚═╝░░░╚═╝░░╚═╝╚══════╝╚═╝░░╚══╝╚═╝░╚════╝░╚═╝░░╚═╝╚═════╝░       ");
        $this->getLogger()->warning(TextFormat::GOLD . "#                                                                                                             ");
        $this->getLogger()->warning(TextFormat::GOLD . "###############################################################################################################");
        $this->getConfig();
        $this->saveResource("config.yml");
        $config = new Config($this->getDataFolder() . "config.yml" , Config::YAML);
        if (empty($config->get("*"))) {
            $config->set("#", "###############################################################################################################");
            $config->set("#2", "#                                                                                                             ");
            $config->set("#3", "#                     ██████╗░██╗░░░██╗  ████████╗██████╗░███████╗███╗░░██╗██╗░█████╗░██╗░░██╗██████╗░       ");
            $config->set("#4", "#                     ██████╗░██╗░░░██╗  ████████╗██████╗░███████╗███╗░░██╗██╗░█████╗░██╗░░██╗██████╗░       ");
            $config->set("#5", "#                     ██╔══██╗╚██╗░██╔╝  ╚══██╔══╝██╔══██╗██╔════╝████╗░██║██║██╔══██╗██║░░██║██╔══██╗      ");
            $config->set("#6", "#                     ██████╦╝░╚████╔╝░  ░░░██║░░░██████╔╝█████╗░░██╔██╗██║██║██║░░╚═╝███████║██║░░██║       ");
            $config->set("#7", "#                     ██████╦╝░╚████╔╝░  ░░░██║░░░██████╔╝█████╗░░██╔██╗██║██║██║░░╚═╝███████║██║░░██║       ");
            $config->set("#8", "#                     ██╔══██╗░░╚██╔╝░░  ░░░██║░░░██╔══██╗██╔══╝░░██║╚████║██║██║░░██╗██╔══██║██║░░██║       ");
            $config->set("#9", "#                     ██████╦╝░░░██║░░░  ░░░██║░░░██║░░██║███████╗██║░╚███║██║╚█████╔╝██║░░██║██████╔╝       ");
            $config->set("#0", "#                     ╚═════╝░░░░╚═╝░░░  ░░░╚═╝░░░╚═╝░░╚═╝╚══════╝╚═╝░░╚══╝╚═╝░╚════╝░╚═╝░░╚═╝╚═════╝░       ");
            $config->set("#11", "#                                                                                                             ");
            $config->set("#12", "###############################################################################################################");
            $config->set("Text", "Abonniert TrenicHD \nhttps://www.youtube.com/channel/UCkH1MkgKy72wGbX8x4mt2yA?view_as=subscriber");
        }

        $config->save();

        $this->saveResource("config.yml");
        @mkdir($this->getDataFolder());
        $this->prefix = $config->get("*");
        $this->getServer()->getPluginManager()->registerEvents($this , $this);

    }

    public function onDisable()
    {
        $this->getLogger()->info("Plugin Deaktiviert");
        $this->getLogger()->info(TextFormat::GOLD . "LobbySystem wird Deaktiviert....");
        $this->getLogger()->error(TextFormat::DARK_RED . "####################################################################################################################################################################");
        $this->getLogger()->error(TextFormat::GOLD . "#                                                                                                             ");
        $this->getLogger()->error(TextFormat::AQUA . "#                      ▄████▄   ▒█████  ▓█████▄ ▓█████     ▄▄▄▄   ▓██   ██▓   ▄▄▄█████▓ ██▀███  ▓█████  ███▄    █  ██▓ ▄████▄   ██░ ██ ▓█████▄        ");
        $this->getLogger()->error(TextFormat::GREEN . "#                     ▒██▀ ▀█  ▒██▒  ██▒▒██▀ ██▌▓█   ▀    ▓█████▄  ▒██  ██▒   ▓  ██▒ ▓▒▓██ ▒ ██▒▓█   ▀  ██ ▀█   █ ▓██▒▒██▀ ▀█  ▓██░ ██▒▒██▀ ██▌     ");
        $this->getLogger()->error(TextFormat::AQUA . "#                     ▒▓█    ▄ ▒██░  ██▒░██   █▌▒███      ▒██▒ ▄██  ▒██ ██░   ▒ ▓██░ ▒░▓██ ░▄█ ▒▒███   ▓██  ▀█ ██▒▒██▒▒▓█    ▄ ▒██▀▀██░░██   █▌       ");
        $this->getLogger()->error(TextFormat::GREEN . "#                    ▒▓▓▄ ▄██▒▒██   ██░░▓█▄   ▌▒▓█  ▄    ▒██░█▀    ░ ▐██▓░   ░ ▓██▓ ░ ▒██▀▀█▄  ▒▓█  ▄ ▓██▒  ▐▌██▒░██░▒▓▓▄ ▄██▒░▓█ ░██ ░▓█▄   ▌       ");
        $this->getLogger()->error(TextFormat::AQUA . "#                     ▒ ▓███▀ ░░ ████▓▒░░▒████▓ ░▒████▒   ░▓█  ▀█▓  ░ ██▒▓░     ▒██▒ ░ ░██▓ ▒██▒░▒████▒▒██░   ▓██░░██░▒ ▓███▀ ░░▓█▒░██▓░▒████▓        ");
        $this->getLogger()->error(TextFormat::GREEN . "#                    ░ ░▒ ▒  ░░ ▒░▒░▒░  ▒▒▓  ▒ ░░ ▒░ ░   ░▒▓███▀▒   ██▒▒▒      ▒ ░░   ░ ▒▓ ░▒▓░░░ ▒░ ░░ ▒░   ▒ ▒ ░▓  ░ ░▒ ▒  ░ ▒ ░░▒░▒ ▒▒▓  ▒     ");
        $this->getLogger()->error(TextFormat::GOLD . "#                       ░  ▒     ░ ▒ ▒░  ░ ▒  ▒  ░ ░  ░   ▒░▒   ░  ▓██ ░▒░        ░      ░▒ ░ ▒░ ░ ░  ░░ ░░   ░ ▒░ ▒ ░  ░  ▒    ▒ ░▒░ ░ ░ ▒  ▒                                                                                         ");
        $this->getLogger()->error(TextFormat::GOLD . "#                     ░        ░ ░ ░ ▒   ░ ░  ░    ░       ░    ░  ▒ ▒ ░░       ░        ░░   ░    ░      ░   ░ ░  ▒ ░░         ░  ░░ ░ ░ ░  ░ ");
        $this->getLogger()->error(TextFormat::GOLD . "#                     ░ ░          ░ ░     ░       ░  ░    ░       ░ ░                    ░        ░  ░         ░  ░  ░ ░       ░  ░  ░   ░    ");
        $this->getLogger()->error(TextFormat::GOLD . "#                     ░                  ░                      ░  ░ ░                                                ░                 ░      ");
        $this->getLogger()->error(TextFormat::DARK_RED . "#######################################################################################################################################################################");
        $this->getLogger()->error("Plugin Deaktiviert!");
    }

    public function onCommand(CommandSender $player, Command $cmd, string $label, array $args): bool
    {

        switch ($cmd->getName()) {
            case "news":
                if ($player instanceof Player) {
                    $this->news($player);
                }
                break;
        }
        return true;

    }

    public function news($player)
    {

        $api = $this->getServer()->getPluginManager()->getPlugin("FormAPI");
        $form = $api->createSimpleForm(function (Player $player, int $data = null) {
            $result = $data;
            if ($result === null) {
                return true;
            }
            switch ($result) {
                case 0:
                    $this->ok($player);
                    break;
            }
        });
        $config = new Config($this->getDataFolder() . "config.yml" , Config::YAML);
        $form->setTitle("§l§4News");
        $form->setContent($config->get("Text"));
        $form->addButton(TextFormat::GOLD . "Zrück");
        $form->sendToPlayer($player);
        return true;
    }

    public function ok($player)
    {
        $player->sendMessage("Du hast die News Verlassen!");
    }


}