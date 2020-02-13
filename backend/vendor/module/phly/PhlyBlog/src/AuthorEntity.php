<?php declare(strict_types=1);

namespace PhlyBlog;

use Laminas\InputFilter\InputFilterInterface;
use PhlyCommon\Entity as EntityDefinition;

class AuthorEntity implements EntityDefinition
{
    protected $filter;

    protected $id;
    protected $name;
    protected $email;
    protected $url;

    public function isValid()
    {
        // Validate against the input filter
        $filter = $this->getInputFilter();
        $filter->setData($this->toArray());
        $valid = $filter->isValid();

        // If valid, push the filtered values back into the object
        if ($valid) {
            $this->fromArray($filter->getValues());
        }

        // Return validation result
        return $valid;
    }

    public function getInputFilter()
    {
        if (null === $this->filter) {
            $this->setInputFilter(new Filter\AuthorFilter());
        }
        return $this->filter;
    }

    public function setInputFilter(InputFilterInterface $filter)
    {
        $this->filter = $filter;
        return $this;
    }

    /**
     * Cast object to array
     *
     * @return array
     */
    public function toArray()
    {
        return [
            'id' => $this->getId(),
            'name' => $this->getName(),
            'email' => $this->getEmail(),
            'url' => $this->getUrl(),
        ];
    }

    /**
     * Get value for id
     *
     * This is a "short name" identifier for the author.
     *
     * @return string
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set value for id
     *
     * This is a "short name" identifier for the author.
     *
     * @param string id
     * @return AuthorEntity
     */
    public function setId($id)
    {
        $this->id = (string)$id;
        return $this;
    }

    /**
     * Get name
     *
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * Set full name of author
     *
     * @param string name
     * @return AuthorEntity
     */
    public function setName($name)
    {
        $this->name = (string)$name;
        return $this;
    }

    /**
     * Get email
     *
     * @return string
     */
    public function getEmail()
    {
        return $this->email;
    }

    /**
     * Set email
     *
     * @param string email
     * @return AuthorEntity
     */
    public function setEmail($email)
    {
        $this->email = (string)$email;
        return $this;
    }

    /**
     * Get author url
     *
     * @return string
     */
    public function getUrl()
    {
        return $this->url;
    }

    /**
     * Set author url
     *
     * @param string url
     * @return AuthorEntity
     */
    public function setUrl($url)
    {
        $this->url = (string)$url;
        return $this;
    }

    /**
     * Populate object from array
     *
     * @param array $array
     * @return AuthorEntity
     */
    public function fromArray(array $array)
    {
        foreach ($array as $key => $value) {
            switch ($key) {
                case 'id':
                case 'name':
                case 'email':
                case 'url':
                    $method = 'set' . $key;
                    $this->$method($value);
                    break;
                default:
                    // Unknown; ignore
                    break;
            }
        }
        return $this;
    }
}
