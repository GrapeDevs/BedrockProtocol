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
use pocketmine\network\mcpe\protocol\types\ActorEvent;

class ActorEventPacket extends DataPacket implements ClientboundPacket, ServerboundPacket{
	public const NETWORK_ID = ProtocolInfo::ACTOR_EVENT_PACKET;

	public int $actorRuntimeId;
	/** @see ActorEvent */
	public int $eventId;
	public int $eventData = 0;

	/**
	 * @generate-create-func
	 */
	public static function create(int $actorRuntimeId, int $eventId, int $eventData) : self{
		$result = new self;
		$result->actorRuntimeId = $actorRuntimeId;
		$result->eventId = $eventId;
		$result->eventData = $eventData;
		return $result;
	}

	protected function decodePayload(PacketSerializer $in) : void{
		$this->actorRuntimeId = $in->getActorRuntimeId();
		$this->eventId = $in->getByte();
		$this->eventData = $in->getVarInt();
	}

	protected function encodePayload(PacketSerializer $out) : void{
		$out->putActorRuntimeId($this->actorRuntimeId);
		$out->putByte($this->eventId);
		$out->putVarInt($this->eventData);
	}

	public function handle(PacketHandlerInterface $handler) : bool{
		return $handler->handleActorEvent($this);
	}
}
