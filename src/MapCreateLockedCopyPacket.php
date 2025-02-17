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

class MapCreateLockedCopyPacket extends DataPacket implements ServerboundPacket{
	public const NETWORK_ID = ProtocolInfo::MAP_CREATE_LOCKED_COPY_PACKET;

	public int $originalMapId;
	public int $newMapId;

	/**
	 * @generate-create-func
	 */
	public static function create(int $originalMapId, int $newMapId) : self{
		$result = new self;
		$result->originalMapId = $originalMapId;
		$result->newMapId = $newMapId;
		return $result;
	}

	protected function decodePayload(PacketSerializer $in) : void{
		$this->originalMapId = $in->getActorUniqueId();
		$this->newMapId = $in->getActorUniqueId();
	}

	protected function encodePayload(PacketSerializer $out) : void{
		$out->putActorUniqueId($this->originalMapId);
		$out->putActorUniqueId($this->newMapId);
	}

	public function handle(PacketHandlerInterface $handler) : bool{
		return $handler->handleMapCreateLockedCopy($this);
	}
}
