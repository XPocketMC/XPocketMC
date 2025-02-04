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
 * @author xpocketmc Team
 * @link http://www.xpocketmc.net/
 *
 *
 */

declare(strict_types=1);

namespace xpocketmc\world\sound;

use xpocketmc\block\Block;
use xpocketmc\math\Vector3;
use xpocketmc\network\mcpe\convert\TypeConverter;
use xpocketmc\network\mcpe\protocol\LevelSoundEventPacket;
use xpocketmc\network\mcpe\protocol\types\LevelSoundEvent;

final class PressurePlateDeactivateSound implements Sound{

	public function __construct(
		private readonly Block $block
	){}

	public function encode(Vector3 $pos) : array{
		return [LevelSoundEventPacket::nonActorSound(
			LevelSoundEvent::PRESSURE_PLATE_CLICK_OFF,
			$pos,
			false,
			TypeConverter::getInstance()->getBlockTranslator()->internalIdToNetworkId($this->block->getStateId())
		)];
	}
}