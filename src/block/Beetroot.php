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

namespace xpocketmc\block;

use xpocketmc\block\utils\FortuneDropHelper;
use xpocketmc\item\Item;
use xpocketmc\item\VanillaItems;

class Beetroot extends Crops{

	public function getDropsForCompatibleTool(Item $item) : array{
		if($this->age >= self::MAX_AGE){
			return [
				VanillaItems::BEETROOT(),
				VanillaItems::BEETROOT_SEEDS()->setCount(FortuneDropHelper::binomial($item, 0))
			];
		}

		return [
			VanillaItems::BEETROOT_SEEDS()
		];
	}

	public function asItem() : Item{
		return VanillaItems::BEETROOT_SEEDS();
	}
}