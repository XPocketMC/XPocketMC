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

namespace xpocketmc\world\particle;

use xpocketmc\item\Item;
use xpocketmc\math\Vector3;
use xpocketmc\network\mcpe\convert\TypeConverter;
use xpocketmc\network\mcpe\protocol\LevelEventPacket;
use xpocketmc\network\mcpe\protocol\types\ParticleIds;

class ItemBreakParticle implements Particle{
	public function __construct(private Item $item){}

	public function encode(Vector3 $pos) : array{
		[$id, $meta] = TypeConverter::getInstance()->getItemTranslator()->toNetworkId($this->item);
		return [LevelEventPacket::standardParticle(ParticleIds::ITEM_BREAK, ($id << 16) | $meta, $pos)];
	}
}