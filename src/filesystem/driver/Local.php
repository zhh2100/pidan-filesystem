<?php
declare (strict_types = 1);

namespace pidan\filesystem\driver;

use League\Flysystem\AdapterInterface;
use League\Flysystem\Adapter\Local as LocalAdapter;
use pidan\filesystem\Driver;

class Local extends Driver
{
    /**
     * 配置参数
     * @var array
     */
    protected $config = [
        'root' => '',
    ];

    protected function createAdapter(): AdapterInterface
    {
        $permissions = $this->config['permissions'] ?? [];

        $links = ($this->config['links'] ?? null) === 'skip'
        ? LocalAdapter::SKIP_LINKS
        : LocalAdapter::DISALLOW_LINKS;

        return new LocalAdapter(
            $this->config['root'],
            LOCK_EX,
            $links,
            $permissions
        );
    }

    /**
     * 获取文件访问地址
     * @param string $path 文件路径
     * @return string
     */
    public function url(string $path): string
    {
        $path = str_replace('\\', '/', $path);

        if (isset($this->config['url'])) {
            return $this->concatPathToUrl($this->config['url'], $path);
        }
        return parent::url($path);
    }
}
