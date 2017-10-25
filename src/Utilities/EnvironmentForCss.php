<?php namespace Ivacuum\Generic\Utilities;

class EnvironmentForCss
{
    protected $user_agent;

    public function __construct(?string $user_agent)
    {
        $this->user_agent = mb_strtolower($user_agent);
    }

    public function __toString(): string
    {
        return implode(' ', array_merge(
            $this->browserClasses(),
            $this->mobileOrDesktopClasses(),
            $this->operatingSystemClasses()
        ));
    }

    public function browserClasses(): array
    {
        if (preg_match('/msie|trident/', $this->user_agent) && !preg_match('/opera/', $this->user_agent)) {
            return ['ie'];
        } elseif (preg_match('/edge/', $this->user_agent)) {
            return ['edge'];
        } elseif (preg_match('/firefox/', $this->user_agent)) {
            return ['firefox'];
        } elseif (preg_match('/safari/', $this->user_agent) && !preg_match('/chrome/', $this->user_agent)) {
            return ['safari'];
        } elseif (preg_match('/opera|opr/', $this->user_agent)) {
            return ['opera'];
        } elseif (preg_match('/chrome/', $this->user_agent)) {
            return ['chrome'];
        }

        return [];
    }

    public function isCrawler(): bool
    {
        return preg_match('/(bot|spider)\//i', $this->user_agent);
    }

    public function isMobile(): bool
    {
        return preg_match('/Android|webOS|iPhone|iPad|iPod|BlackBerry|Windows Phone|Opera Mini/i', $this->user_agent);
    }

    public function mobileOrDesktopClasses(): array
    {
        return $this->isMobile() ? ['is-mobile'] : ['is-desktop'];
    }

    public function operatingSystemClasses(): array
    {
        if (preg_match('/win/', $this->user_agent)) {
            return ['windows'];
        } elseif (preg_match('/iphone|ipad|ipod/', $this->user_agent)) {
            return ['ios'];
        } elseif (preg_match('/mac/', $this->user_agent)) {
            return ['macos'];
        } elseif (preg_match('/linux/', $this->user_agent)) {
            return ['linux'];
        } elseif (preg_match('/android/', $this->user_agent)) {
            return ['android'];
        }

        return [];
    }
}
