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
use pocketmine\network\mcpe\protocol\types\EducationUriResource;

class EduUriResourcePacket extends DataPacket implements ClientboundPacket{
	public const NETWORK_ID = ProtocolInfo::EDU_URI_RESOURCE_PACKET;

	private EducationUriResource $resource;

	/**
	 * @generate-create-func
	 */
	public static function create(EducationUriResource $resource) : self{
		$result = new self;
		$result->resource = $resource;
		return $result;
	}

	public function getResource() : EducationUriResource{ return $this->resource; }

	protected function decodePayload(PacketSerializer $in) : void{
		$this->resource = EducationUriResource::read($in);
	}

	protected function encodePayload(PacketSerializer $out) : void{
		$this->resource->write($out);
	}

	public function handle(PacketHandlerInterface $handler) : bool{
		return $handler->handleEduUriResource($this);
	}
}
