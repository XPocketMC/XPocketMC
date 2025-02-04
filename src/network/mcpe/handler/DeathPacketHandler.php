<?php

/*
 *
 *  ____            _        _   __  __ _                  __  __ ____
 * |  _ \ ___   ___| | _____| |_|  \/  (_)_ __   ___      |  \/  |  _ \
 * | |_) / _ \ / __| |/ / _ \ __| |\/| | | '_ \ / _ \_____| |\/| | |_) |
 * |  __/ (_) | (__|   <  __/ |_| |  | | | | | |  __/_____| |  | |  __/
 * |_|   \___/ \___|_|\_\___|\__|_|  |_|_|_| |_|\___|     |_|  |_|_|
 *
 * This program is free software: you can redistribute it and/or modify
 * it under the terms of the GNU Lesser General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 *
 * @author xpocketmc Team
 * @link http://www.xpocketmc.net/
 *
 *
 */

declare(strict_types=1);

namespace xpocketmc\network\mcpe\handler;

use xpocketmc\lang\Translatable;
use xpocketmc\network\mcpe\InventoryManager;
use xpocketmc\network\mcpe\NetworkSession;
use xpocketmc\network\mcpe\protocol\ContainerClosePacket;
use xpocketmc\network\mcpe\protocol\DeathInfoPacket;
use xpocketmc\network\mcpe\protocol\PlayerActionPacket;
use xpocketmc\network\mcpe\protocol\RespawnPacket;
use xpocketmc\network\mcpe\protocol\types\PlayerAction;
use xpocketmc\player\Player;

class DeathPacketHandler extends PacketHandler{
	public function __construct(
		private Player $player,
		private NetworkSession $session,
		private InventoryManager $inventoryManager,
		private Translatable|string $deathMessage
	){}

	public function setUp() : void{
		$this->session->sendDataPacket(RespawnPacket::create(
			$this->player->getOffsetPosition($this->player->getSpawn()),
			RespawnPacket::SEARCHING_FOR_SPAWN,
			$this->player->getId()
		));

		/** @var string[] $parameters */
		$parameters = [];
		if($this->deathMessage instanceof Translatable){
			$language = $this->player->getLanguage();
			if(!$this->player->getServer()->isLanguageForced()){
				[$message, $parameters] = $this->session->prepareClientTranslatableMessage($this->deathMessage);
			}else{
				$message = $language->translate($this->deathMessage);
			}
		}else{
			$message = $this->deathMessage;
		}
		$this->session->sendDataPacket(DeathInfoPacket::create($message, $parameters));
	}

	public function handlePlayerAction(PlayerActionPacket $packet) : bool{
		if($packet->action === PlayerAction::RESPAWN){
			$this->player->respawn();
			return true;
		}

		return false;
	}

	public function handleContainerClose(ContainerClosePacket $packet) : bool{
		$this->inventoryManager->onClientRemoveWindow($packet->windowId);
		return true;
	}

	public function handleRespawn(RespawnPacket $packet) : bool{
		if($packet->respawnState === RespawnPacket::CLIENT_READY_TO_SPAWN){
			$this->session->sendDataPacket(RespawnPacket::create(
				$this->player->getOffsetPosition($this->player->getSpawn()),
				RespawnPacket::READY_TO_SPAWN,
				$this->player->getId()
			));
			return true;
		}
		return false;
	}
}