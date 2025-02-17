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

class BlockPickRequestPacket extends DataPacket implements ServerboundPacket{
	public const NETWORK_ID = ProtocolInfo::BLOCK_PICK_REQUEST_PACKET;

	public BlockPosition $blockPosition;
	public bool $addUserData = false;
	public int $hotbarSlot;

	/**
	 * @generate-create-func
	 */
	public static function create(BlockPosition $blockPosition, bool $addUserData, int $hotbarSlot) : self{
		$result = new self;
		$result->blockPosition = $blockPosition;
		$result->addUserData = $addUserData;
		$result->hotbarSlot = $hotbarSlot;
		return $result;
	}

	protected function decodePayload(PacketSerializer $in) : void{
		$this->blockPosition = $in->getSignedBlockPosition();
		$this->addUserData = $in->getBool();
		$this->hotbarSlot = $in->getByte();
	}

	protected function encodePayload(PacketSerializer $out) : void{
		$out->putSignedBlockPosition($this->blockPosition);
		$out->putBool($this->addUserData);
		$out->putByte($this->hotbarSlot);
	}

	public function handle(PacketHandlerInterface $handler) : bool{
		return $handler->handleBlockPickRequest($this);
	}
}
