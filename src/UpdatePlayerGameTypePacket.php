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
 * @author PocketMine Team
 * @link http://www.pocketmine.net/
 *
 *
 */

declare(strict_types=1);

namespace pocketmine\network\mcpe\protocol;

#include <rules/DataPacket.h>

use pocketmine\network\mcpe\protocol\serializer\PacketSerializer;
use pocketmine\network\mcpe\protocol\types\GameMode;

class UpdatePlayerGameTypePacket extends DataPacket implements ClientboundPacket{
	public const NETWORK_ID = ProtocolInfo::UPDATE_PLAYER_GAME_TYPE_PACKET;

	/** @see GameMode */
	private int $gameMode;
	private int $playerActorUniqueId;

	/**
	 * @generate-create-func
	 */
	public static function create(int $gameMode, int $playerActorUniqueId) : self{
		$result = new self;
		$result->gameMode = $gameMode;
		$result->playerActorUniqueId = $playerActorUniqueId;
		return $result;
	}

	public function getGameMode() : int{ return $this->gameMode; }

	public function getPlayerActorUniqueId() : int{ return $this->playerActorUniqueId; }

	protected function decodePayload(PacketSerializer $in) : void{
		$this->gameMode = $in->getVarInt();
		$this->playerActorUniqueId = $in->getActorUniqueId();
	}

	protected function encodePayload(PacketSerializer $out) : void{
		$out->putVarInt($this->gameMode);
		$out->putActorUniqueId($this->playerActorUniqueId);
	}

	public function handle(PacketHandlerInterface $handler) : bool{
		return $handler->handleUpdatePlayerGameType($this);
	}
}
