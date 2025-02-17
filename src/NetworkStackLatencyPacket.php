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

class NetworkStackLatencyPacket extends DataPacket implements ClientboundPacket, ServerboundPacket{
	public const NETWORK_ID = ProtocolInfo::NETWORK_STACK_LATENCY_PACKET;

	public int $timestamp;
	public bool $needResponse;

	/**
	 * @generate-create-func
	 */
	public static function create(int $timestamp, bool $needResponse) : self{
		$result = new self;
		$result->timestamp = $timestamp;
		$result->needResponse = $needResponse;
		return $result;
	}

	public static function request(int $timestamp) : self{
		return self::create($timestamp, true);
	}

	public static function response(int $timestamp) : self{
		return self::create($timestamp, false);
	}

	protected function decodePayload(PacketSerializer $in) : void{
		$this->timestamp = $in->getLLong();
		$this->needResponse = $in->getBool();
	}

	protected function encodePayload(PacketSerializer $out) : void{
		$out->putLLong($this->timestamp);
		$out->putBool($this->needResponse);
	}

	public function handle(PacketHandlerInterface $handler) : bool{
		return $handler->handleNetworkStackLatency($this);
	}
}
