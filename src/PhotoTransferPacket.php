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

class PhotoTransferPacket extends DataPacket implements ClientboundPacket{
	public const NETWORK_ID = ProtocolInfo::PHOTO_TRANSFER_PACKET;

	public string $photoName;
	public string $photoData;
	public string $bookId; //photos are stored in a sibling directory to the games folder (screenshots/(some UUID)/bookID/example.png)
	public int $type;
	public int $sourceType;
	public int $ownerActorUniqueId;
	public string $newPhotoName; //???

	/**
	 * @generate-create-func
	 */
	public static function create(
		string $photoName,
		string $photoData,
		string $bookId,
		int $type,
		int $sourceType,
		int $ownerActorUniqueId,
		string $newPhotoName,
	) : self{
		$result = new self;
		$result->photoName = $photoName;
		$result->photoData = $photoData;
		$result->bookId = $bookId;
		$result->type = $type;
		$result->sourceType = $sourceType;
		$result->ownerActorUniqueId = $ownerActorUniqueId;
		$result->newPhotoName = $newPhotoName;
		return $result;
	}

	protected function decodePayload(PacketSerializer $in) : void{
		$this->photoName = $in->getString();
		$this->photoData = $in->getString();
		$this->bookId = $in->getString();
		$this->type = $in->getByte();
		$this->sourceType = $in->getByte();
		$this->ownerActorUniqueId = $in->getLLong(); //...............
		$this->newPhotoName = $in->getString();
	}

	protected function encodePayload(PacketSerializer $out) : void{
		$out->putString($this->photoName);
		$out->putString($this->photoData);
		$out->putString($this->bookId);
		$out->putByte($this->type);
		$out->putByte($this->sourceType);
		$out->putLLong($this->ownerActorUniqueId);
		$out->putString($this->newPhotoName);
	}

	public function handle(PacketHandlerInterface $handler) : bool{
		return $handler->handlePhotoTransfer($this);
	}
}
