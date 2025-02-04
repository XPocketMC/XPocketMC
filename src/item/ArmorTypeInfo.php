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

namespace xpocketmc\item;

class ArmorTypeInfo{
	private ArmorMaterial $material;

	public function __construct(
		private int $defensePoints,
		private int $maxDurability,
		private int $armorSlot,
		private int $toughness = 0,
		private bool $fireProof = false,
		?ArmorMaterial $material = null
	){
		$this->material = $material ?? VanillaArmorMaterials::LEATHER();
	}

	public function getDefensePoints() : int{
		return $this->defensePoints;
	}

	public function getMaxDurability() : int{
		return $this->maxDurability;
	}

	public function getArmorSlot() : int{
		return $this->armorSlot;
	}

	public function getToughness() : int{
		return $this->toughness;
	}

	public function isFireProof() : bool{
		return $this->fireProof;
	}

	public function getMaterial() : ArmorMaterial{
		return $this->material;
	}
}