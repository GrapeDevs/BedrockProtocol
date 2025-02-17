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
use pocketmine\network\mcpe\protocol\types\inventory\ContainerIds;

class PlayerHotbarPacket extends DataPacket implements ClientboundPacket, ServerboundPacket{
	public const NETWORK_ID = ProtocolInfo::PLAYER_HOTBAR_PACKET;

	public int $selectedHotbarSlot;
	public int $windowId = ContainerIds::INVENTORY;
	public bool $selectHotbarSlot = true;

	/**
	 * @generate-create-func
	 */
	public static function create(int $selectedHotbarSlot, int $windowId, bool $selectHotbarSlot) : self{
		$result = new self;
		$result->selectedHotbarSlot = $selectedHotbarSlot;
		$result->windowId = $windowId;
		$result->selectHotbarSlot = $selectHotbarSlot;
		return $result;
	}

	protected function decodePayload(PacketSerializer $in) : void{
		$this->selectedHotbarSlot = $in->getUnsignedVarInt();
		$this->windowId = $in->getByte();
		$this->selectHotbarSlot = $in->getBool();
	}

	protected function encodePayload(PacketSerializer $out) : void{
		$out->putUnsignedVarInt($this->selectedHotbarSlot);
		$out->putByte($this->windowId);
		$out->putBool($this->selectHotbarSlot);
	}

	public function handle(PacketHandlerInterface $handler) : bool{
		return $handler->handlePlayerHotbar($this);
	}
}
