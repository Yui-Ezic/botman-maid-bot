<?php


namespace App\Entities\Bot;


class Photo
{
    /**
     * @var int
     */
    private $id;

    /**
     * @var string
     */
    private $url;

    /**
     * @var int
     */
    private $width;

    /**
     * @var int
     */
    private $height;

    /**
     * Photo constructor.
     *
     * @param int $id
     * @param string $url
     * @param int $width
     * @param int $height
     */
    public function __construct(int $id, string $url, int $width, int $height)
    {
        $this->id = $id;
        $this->url = $url;
        $this->width = $width;
        $this->height = $height;
    }

    /**
     * @return int
     */
    public function getId(): int
    {
        return $this->id;
    }

    /**
     * @return string
     */
    public function getUrl(): string
    {
        return $this->url;
    }

    /**
     * @return int
     */
    public function getWidth(): int
    {
        return $this->width;
    }

    /**
     * @return int
     */
    public function getHeight(): int
    {
        return $this->height;
    }
}