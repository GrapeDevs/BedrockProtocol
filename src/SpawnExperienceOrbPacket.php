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

use pocketmine\math\Vector3;
use pocketmine\network\mcpe\protocol\serializer\PacketSerializer;

class SpawnExperienceOrbPacket extends DataPacket implements ServerboundPacket{
	public const NETWORK_ID = ProtocolInfo::SPAWN_EXPERIENCE_ORB_PACKET;

	public Vector3 $position;
	public int $amount;

	/**
	 * @generate-create-func
	 */
	public static function create(Vector3 $position, int $amount) : self{
		$result = new self;
		$result->position = $position;
		$result->amount = $amount;
		return $result;
	}

	protected function decodePayload(PacketSerializer $in) : void{
		$this->position = $in->getVector3();
		$this->amount = $in->getVarInt();
	}

	protected function encodePayload(PacketSerializer $out) : void{
		$out->putVector3($this->position);
		$out->putVarInt($this->amount);
	}

	public function handle(PacketHandlerInterface $handler) : bool{
		return $handler->handleSpawnExperienceOrb($this);
	}
}
