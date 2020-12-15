<?php

declare(strict_types=1);

namespace Yiisoft\Cache;

use Psr\SimpleCache\CacheInterface;

/**
 * PrefixedCache decorates any PSR-16 cache to add global prefix. It is added to every cache key so that it is unique
 * globally in the whole cache storage. It is recommended that you set a unique cache key prefix for each application
 * if the same cache storage is being used by different applications.
 *
 * ```php
 * $cache = new PrefixedCache(new ArrayCache(), 'my_app_');
 * $cache->set('answer', 42); // Will set 42 to my_app_answer key.
 * ```
 */
final class PrefixedCache implements CacheInterface
{
    private CacheInterface $cache;
    private string $prefix;

    /**
     * @param CacheInterface $cache PSR-16 cache to add prefix to.
     * @param string $prefix Prefix to use for all cache keys.
     */
    public function __construct(CacheInterface $cache, string $prefix)
    {
        $this->cache = $cache;
        $this->prefix = $prefix;
    }

    public function get($key, $default = null)
    {
        return $this->cache->get($this->prefix . $key, $default);
    }

    public function set($key, $value, $ttl = null)
    {
        return $this->cache->set($this->prefix . $key, $value, $ttl);
    }

    public function delete($key)
    {
        return $this->cache->delete($this->prefix . $key);
    }

    public function clear()
    {
        return $this->cache->clear();
    }

    public function getMultiple($keys, $default = null)
    {
        $prefixedKeys = [];
        foreach ($keys as $key) {
            $prefixedKeys[] = $this->prefix . $key;
        }

        return $this->cache->getMultiple($prefixedKeys, $default);
    }

    public function setMultiple($values, $ttl = null)
    {
        $prefixedValues = [];
        foreach ($values as $key => $value) {
            $prefixedValues[$this->prefix . $key] = $value;
        }
        return $this->cache->setMultiple($prefixedValues, $ttl);
    }

    public function deleteMultiple($keys)
    {
        $prefixedKeys = [];
        foreach ($keys as $key) {
            $prefixedKeys[] = $this->prefix . $key;
        }

        return $this->cache->deleteMultiple($prefixedKeys);
    }

    public function has($key)
    {
        return $this->cache->has($this->prefix . $key);
    }
}