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

class SetDisplayObjectivePacket extends DataPacket implements ClientboundPacket{
	public const NETWORK_ID = ProtocolInfo::SET_DISPLAY_OBJECTIVE_PACKET;

	public const DISPLAY_SLOT_LIST = "list";
	public const DISPLAY_SLOT_SIDEBAR = "sidebar";
	public const DISPLAY_SLOT_BELOW_NAME = "belowname";

	public const SORT_ORDER_ASCENDING = 0;
	public const SORT_ORDER_DESCENDING = 1;

	public string $displaySlot;
	public string $objectiveName;
	public string $displayName;
	public string $criteriaName;
	public int $sortOrder;

	/**
	 * @generate-create-func
	 */
	public static function create(string $displaySlot, string $objectiveName, string $displayName, string $criteriaName, int $sortOrder) : self{
		$result = new self;
		$result->displaySlot = $displaySlot;
		$result->objectiveName = $objectiveName;
		$result->displayName = $displayName;
		$result->criteriaName = $criteriaName;
		$result->sortOrder = $sortOrder;
		return $result;
	}

	protected function decodePayload(PacketSerializer $in) : void{
		$this->displaySlot = $in->getString();
		$this->objectiveName = $in->getString();
		$this->displayName = $in->getString();
		$this->criteriaName = $in->getString();
		$this->sortOrder = $in->getVarInt();
	}

	protected function encodePayload(PacketSerializer $out) : void{
		$out->putString($this->displaySlot);
		$out->putString($this->objectiveName);
		$out->putString($this->displayName);
		$out->putString($this->criteriaName);
		$out->putVarInt($this->sortOrder);
	}

	public function handle(PacketHandlerInterface $handler) : bool{
		return $handler->handleSetDisplayObjective($this);
	}
}
