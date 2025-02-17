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
use pocketmine\network\mcpe\protocol\types\BlockPosition;

class PlaySoundPacket extends DataPacket implements ClientboundPacket{
	public const NETWORK_ID = ProtocolInfo::PLAY_SOUND_PACKET;

	public string $soundName;
	public float $x;
	public float $y;
	public float $z;
	public float $volume;
	public float $pitch;

	/**
	 * @generate-create-func
	 */
	public static function create(string $soundName, float $x, float $y, float $z, float $volume, float $pitch) : self{
		$result = new self;
		$result->soundName = $soundName;
		$result->x = $x;
		$result->y = $y;
		$result->z = $z;
		$result->volume = $volume;
		$result->pitch = $pitch;
		return $result;
	}

	protected function decodePayload(PacketSerializer $in) : void{
		$this->soundName = $in->getString();
		$x = $y = $z = 0;
		$blockPosition = $in->getBlockPosition();
		$this->x = $blockPosition->getX() / 8;
		$this->y = $blockPosition->getY() / 8;
		$this->z = $blockPosition->getZ() / 8;
		$this->volume = $in->getLFloat();
		$this->pitch = $in->getLFloat();
	}

	protected function encodePayload(PacketSerializer $out) : void{
		$out->putString($this->soundName);
		$out->putBlockPosition(new BlockPosition((int) ($this->x * 8), (int) ($this->y * 8), (int) ($this->z * 8)));
		$out->putLFloat($this->volume);
		$out->putLFloat($this->pitch);
	}

	public function handle(PacketHandlerInterface $handler) : bool{
		return $handler->handlePlaySound($this);
	}
}
