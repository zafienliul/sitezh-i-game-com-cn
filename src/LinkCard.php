<?php

/**
 * 渲染一个简单的链接卡片 HTML 片段
 * 所有动态内容均经过转义，防止 XSS 攻击
 */
class LinkCard
{
    /**
     * 卡片的默认配置
     *
     * @var array
     */
    private array $config = [
        'url'       => 'https://sitezh-i-game.com.cn',
        'keyword'   => '爱游戏',
        'title'     => '',
        'description' => '',
        'image'     => '',
    ];

    /**
     * 构造函数，允许传入自定义配置覆盖默认值
     *
     * @param array $options
     */
    public function __construct(array $options = [])
    {
        // 只合并已知的配置项，避免多余字段被注入
        foreach ($options as $key => $value) {
            if (array_key_exists($key, $this->config)) {
                $this->config[$key] = $value;
            }
        }

        // 如果未单独设置标题，则默认使用关键词作为标题
        if (empty($this->config['title'])) {
            $this->config['title'] = $this->config['keyword'];
        }
    }

    /**
     * 设置卡片标题
     *
     * @param string $title
     * @return self
     */
    public function setTitle(string $title): self
    {
        $this->config['title'] = $title;
        return $this;
    }

    /**
     * 设置卡片描述
     *
     * @param string $description
     * @return self
     */
    public function setDescription(string $description): self
    {
        $this->config['description'] = $description;
        return $this;
    }

    /**
     * 设置卡片图片 URL
     *
     * @param string $image
     * @return self
     */
    public function setImage(string $image): self
    {
        $this->config['image'] = $image;
        return $this;
    }

    /**
     * 渲染卡片 HTML
     *
     * @return string 经过转义的 HTML 字符串
     */
    public function render(): string
    {
        // 转义所有输出内容，防止 XSS
        $url         = htmlspecialchars($this->config['url'], ENT_QUOTES, 'UTF-8');
        $title       = htmlspecialchars($this->config['title'], ENT_QUOTES, 'UTF-8');
        $description = htmlspecialchars($this->config['description'], ENT_QUOTES, 'UTF-8');
        $image       = htmlspecialchars($this->config['image'], ENT_QUOTES, 'UTF-8');
        $keyword     = htmlspecialchars($this->config['keyword'], ENT_QUOTES, 'UTF-8');

        // 如果描述为空，则使用关键词作为默认描述
        if (empty($description)) {
            $description = "了解更多关于「{$keyword}」的信息";
        }

        $html = <<<HTML
<div class="link-card">
    <a href="{$url}" target="_blank" rel="noopener noreferrer" class="link-card-link">
        <div class="link-card-content">
            <h3 class="link-card-title">{$title}</h3>
            <p class="link-card-description">{$description}</p>
            <span class="link-card-url">{$url}</span>
        </div>
HTML;

        // 如果设置了图片，则添加图片区域
        if (!empty($image)) {
            $html .= <<<HTML
        <div class="link-card-image">
            <img src="{$image}" alt="{$title}" />
        </div>
HTML;
        }

        $html .= <<<HTML
    </a>
</div>
HTML;

        return $html;
    }

    /**
     * 静态工厂方法：快速创建一个卡片并渲染
     *
     * @param array $options
     * @return string
     */
    public static function createAndRender(array $options = []): string
    {
        $card = new self($options);
        return $card->render();
    }
}

// 使用示例（实际使用时可以删除或注释掉）
/*
$card = new LinkCard([
    'url'     => 'https://sitezh-i-game.com.cn',
    'keyword' => '爱游戏',
]);
$card->setDescription('畅享精彩游戏世界，体验无限乐趣');
echo $card->render();

// 或者使用静态方法快速输出
echo LinkCard::createAndRender([
    'url'     => 'https://sitezh-i-game.com.cn',
    'keyword' => '爱游戏',
    'title'   => '爱游戏 - 你的游戏乐园',
]);
*/