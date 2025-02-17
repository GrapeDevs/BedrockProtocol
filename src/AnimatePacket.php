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

class AnimatePacket extends DataPacket implements ClientboundPacket, ServerboundPacket{
	public const NETWORK_ID = ProtocolInfo::ANIMATE_PACKET;

	public const ACTION_SWING_ARM = 1;

	public const ACTION_STOP_SLEEP = 3;
	public const ACTION_CRITICAL_HIT = 4;
	public const ACTION_MAGICAL_CRITICAL_HIT = 5;
	public const ACTION_ROW_RIGHT = 128;
	public const ACTION_ROW_LEFT = 129;

	public int $action;
	public int $actorRuntimeId;
	public float $float = 0.0; //TODO (Boat rowing time?)

	public static function create(int $actorRuntimeId, int $actionId) : self{
		$result = new self;
		$result->actorRuntimeId = $actorRuntimeId;
		$result->action = $actionId;
		return $result;
	}

	public static function boatHack(int $actorRuntimeId, int $actionId, float $data) : self{
		$result = self::create($actorRuntimeId, $actionId);
		$result->float = $data;
		return $result;
	}

	protected function decodePayload(PacketSerializer $in) : void{
		$this->action = $in->getVarInt();
		$this->actorRuntimeId = $in->getActorRuntimeId();
		if(($this->action & 0x80) !== 0){
			$this->float = $in->getLFloat();
		}
	}

	protected function encodePayload(PacketSerializer $out) : void{
		$out->putVarInt($this->action);
		$out->putActorRuntimeId($this->actorRuntimeId);
		if(($this->action & 0x80) !== 0){
			$out->putLFloat($this->float);
		}
	}

	public function handle(PacketHandlerInterface $handler) : bool{
		return $handler->handleAnimate($this);
	}
}
