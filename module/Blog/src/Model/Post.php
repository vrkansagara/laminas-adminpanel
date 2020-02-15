<?php

declare(strict_types=1);

namespace Blog\Model;

class Post
{
    /**
     * @var int
     */
    private $id;

    /**
     * @var string
     */
    private $text;

    /**
     * @var string
     */
    private $title;

    /**
     * @param string $title
     * @param string $text
     * @param int|null $id
     */
    public function __construct($title, $text, $id = null)
    {
        $this->title = $title;
        $this->text = $text;
        $this->id = $id;
    }

    public function exchangeArray(array $data)
    {
        $this->id = ! empty($data['id']) ? $data['id'] : null;
        $this->text = ! empty($data['text']) ? $data['text'] : null;
        $this->title = ! empty($data['title']) ? $data['title'] : null;
    }
    public function getArrayCopy()
    {
        return [
            'id'     => $this->id,
            'text' => $this->text,
            'title'  => $this->title,
        ];
    }
    /**
     * @return int|null
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @return string
     */
    public function getText()
    {
        return $this->text;
    }

    /**
     * @return string
     */
    public function getTitle()
    {
        return $this->title;
    }
}
