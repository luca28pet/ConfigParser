<?php
declare(strict_types=1);

namespace luca28pet\configparser;

class ConfigNode {
	public final function __construct(
		private mixed $data
	) {}

	public function getRawData() : mixed {
		return $this->data;
	}

	public function toInt() : int {
		if (!is_int($this->data)) {
			throw new IncompatibleConfigNodeTypeException('Cannot convert node value to int from type '.gettype($this->data));
		}
		return $this->data;
	}

	public function toString() : string {
		if (!is_string($this->data)) {
			throw new IncompatibleConfigNodeTypeException('Cannot convert node value to string from type '.gettype($this->data));
		}
		return $this->data;
	}

	public function toFloat() : float {
		if (!is_float($this->data)) {
			throw new IncompatibleConfigNodeTypeException('Cannot convert node value to float from type '.gettype($this->data));
		}
		return $this->data;
	}

	public function toBool() : bool {
		if (!is_bool($this->data)) {
			throw new IncompatibleConfigNodeTypeException('Cannot convert node value to bool from type '.gettype($this->data));
		}
		return $this->data;
	}

	/**
	 * @return list<static>
	 */
	public function toList() : array {
		if (!is_array($this->data)) {
			throw new IncompatibleConfigNodeTypeException('Cannot convert node value to list from type '.gettype($this->data));
		}
		return array_values(array_map(fn($d) => new static($d), $this->data));
	}

	public function getListEntry(int $i) : ?ConfigNode {
		$list = $this->toList();
		return $list[$i] ?? null;
	}

	/**
	 * @return \SplObjectStorage<static, static>
	 */
	public function toMap() : \SplObjectStorage {
		if (!is_array($this->data)) {
			throw new IncompatibleConfigNodeTypeException('Cannot convert node value to map from type '.gettype($this->data));
		}
		$ret = new \SplObjectStorage();
		foreach ($this->data as $k => $d) {
			$ret[new static($k)] = new static($d);
		}
		return $ret;
	}

	public function getMapEntry(int|string $key) : ?static {
		if (!is_array($this->data)) {
			throw new IncompatibleConfigNodeTypeException('Cannot convert node value to map from type '.gettype($this->data));
		}
		return isset($this->data[$key]) ? new static($this->data[$key]) : null;
	}
}

