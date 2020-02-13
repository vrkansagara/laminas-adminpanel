<?php declare(strict_types=1);

namespace PhlyBlog;

use Laminas\InputFilter\InputFilterInterface;
use PhlyCommon\Entity as EntityDefinition;
use PhlyCommon\Filter\Timestamp as TimestampFilter;

class EntryEntity implements EntityDefinition
{
    protected static $defaultFilter;
    protected $filter;

    /*
     * identifier/stub
     * title
     * body
     * extended
     * author
     * is_draft
     * is_public
     * created
     * updated
     * tags (array)
     * metadata (array)
     */
    protected $id;
    protected $title;
    protected $body = '';
    protected $extended = '';
    protected $author;
    protected $isDraft = true;
    protected $isPublic = true;
    protected $created;
    protected $updated;
    protected $timezone = 'America/New_York';
    protected $tags = [];
    protected $metadata = [];
    protected $comments = [];
    protected $version = 2;

    /**
     * Overloading: retrieve property
     *
     * Proxies to getters
     *
     * @param string $name
     * @return mixed
     * @throws UnexpectedValueException
     */
    public function __get($name)
    {
        // Booleans:
        if ('is' == substr($name, 0, 2)) {
            if (method_exists($this, $name)) {
                return $this->$name();
            }
        }

        // Check for a getter
        $method = 'get' . ucfirst($name);
        if (method_exists($this, $method)) {
            return $this->$method();
        }

        // Unknown
        throw new \UnexpectedValueException(sprintf(
            'The property "%s" does not exist and cannot be retrieved',
            $name
        ));
    }

    /**
     * Overloading: set property
     *
     * Proxies to setters
     *
     * @param string $name
     * @param mixed $value
     * @return void
     * @throws UnexpectedValueException
     */
    public function __set($name, $value)
    {
        $method = 'set' . ucfirst($name);
        if (method_exists($this, $method)) {
            $this->$method($value);
            return;
        }
        throw new \UnexpectedValueException(sprintf(
            'The property "%s" does not exist and cannot be set',
            $name
        ));
    }

    /**
     * Overloading: property exists
     *
     * @param string $name
     * @return bool
     */
    public function __isset($name)
    {
        return property_exists($this, $name);
    }

    /**
     * Add a tag
     *
     * @param string $tag
     * @return Entry
     */
    public function addTag($tag)
    {
        $this->tags[] = (string)$tag;
        return $this;
    }

    /**
     * Remove a single tag
     *
     * @param string $tag
     * @return void
     */
    public function removeTag($tag)
    {
        if (false !== ($idx = array_search($tag, $this->tags))) {
            unset($this->tags[$idx]);
        }
    }

    /**
     * Does the specific metadata exist?
     *
     * @param scalar $metadata
     * @return bool
     */
    public function hasMetadata($metadata)
    {
        return (isset($this->metadata[$metadata]));
    }

    /**
     * Remove a single metadatum
     *
     * @param null|scalar $key
     * @return bool
     */
    public function removeMetadata($key = null)
    {
        if (null === $key) {
            $this->metadata = [];
            return true;
        }
        if (is_scalar($key) && isset($this->metadata[$key])) {
            unset($this->metadata[$key]);
            return true;
        }
        return false;
    }

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
            $this->setInputFilter(new Filter\EntryFilter());
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
        $return = [
            'id' => $this->getId(),
            'title' => $this->getTitle(),
            'body' => $this->getBody(),
            'extended' => $this->getExtended(),
            'author' => $this->getAuthor(),
            'is_draft' => $this->isDraft(),
            'is_public' => $this->isPublic(),
            'created' => $this->getCreated(),
            'updated' => $this->getUpdated(),
            'timezone' => $this->getTimezone(),
            'tags' => $this->getTags(),
            'metadata' => $this->getMetadata(),
            'version' => $this->getVersion(),
        ];
        if (1 == $this->getVersion()) {
            $return['comments'] = $this->getComments();
        }
        return $return;
    }

    /**
     * Get value for identifier
     *
     * @return string
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     *
     * set value for identifier
     * @param string $value
     * @return Entry
     */
    public function setId($value)
    {
        $this->id = $value;
        return $this;
    }

    /**
     * Get value for title
     *
     * @return string
     */
    public function getTitle()
    {
        return $this->title;
    }

    /**
     * Set value for title
     *
     * @param string $value
     * @return Entry
     */
    public function setTitle($value)
    {
        $this->title = $value;
        if (empty($this->id)) {
            $this->setId(static::makeStub($value));
        }
        return $this;
    }

    public static function makeStub($value)
    {
        $filter = new Filter\Permalink();
        return $filter->filter($value);
    }

    /**
     * Get value for body
     *
     * @return string
     */
    public function getBody()
    {
        return $this->body;
    }

    /**
     * Set value for body
     *
     * @param string $value
     * @return Entry
     */
    public function setBody($value)
    {
        $this->body = $value;
        return $this;
    }

    /**
     * Get value for extended body
     *
     * @return string
     */
    public function getExtended()
    {
        return $this->extended;
    }

    /**
     * Set value for extended body
     *
     * @param string $value
     * @return Entry
     */
    public function setExtended($value)
    {
        $this->extended = $value;
        return $this;
    }

    /**
     * Get value for author
     *
     * @return string|object|array
     */
    public function getAuthor()
    {
        return $this->author;
    }

    /**
     * Set value for author
     *
     * @param string|object|array $value
     * @return Entry
     */
    public function setAuthor($value)
    {
        $this->author = $value;
        return $this;
    }

    /**
     * Is the entry marked as a draft?
     *
     * @return bool
     */
    public function isDraft()
    {
        return $this->isDraft;
    }

    /**
     * Is the entry marked as public?
     *
     * @return bool
     */
    public function isPublic()
    {
        return $this->isPublic;
    }

    /**
     * Get value for created
     *
     * @return int
     */
    public function getCreated()
    {
        if (null === $this->created) {
            $this->setCreated($_SERVER['REQUEST_TIME']);
        }
        return $this->created;
    }

    /**
     * Set timestamp when entry was created
     *
     * @param DateTime|MongoDate|string|int $value
     * @return Entry
     */
    public function setCreated($value)
    {
        $filter = new TimestampFilter();
        $this->created = $filter->filter($value);
        return $this;
    }

    /**
     * Get value when entry updated
     *
     * @return int
     */
    public function getUpdated()
    {
        if (null === $this->updated) {
            $this->setUpdated($_SERVER['REQUEST_TIME']);
        }
        return $this->updated;
    }

    /**
     * set value when entry updated
     *
     * @param int|string|MongoDate|DateTime $value
     * @return Entry
     */
    public function setUpdated($value)
    {
        $filter = new TimestampFilter();
        $this->updated = $filter->filter($value);
        return $this;
    }

    /**
     * Get timezone value
     *
     * @return string
     */
    public function getTimezone()
    {
        return $this->timezone;
    }

    /**
     * Set timezone for timestamps
     *
     * @param string $value
     * @return Entry
     */
    public function setTimezone($value)
    {
        $this->timezone = $value;
        return $this;
    }

    /**
     * Get tags
     *
     * @return array
     */
    public function getTags()
    {
        $tags = array_unique($this->tags, SORT_STRING);
        return $tags;
    }

    /**
     * Set tags (en masse)
     *
     * Will overwrite tags; pass an empty array to clear all tags.
     *
     * @param array $value
     * @return Entry
     */
    public function setTags(array $value)
    {
        $this->tags = $value;
        return $this;
    }

    /**
     * Get individual metadata or the entire set
     *
     * @param null|scalar $metadata
     * @param mixed $default
     * @return mixed
     */
    public function getMetadata($metadata = null, $default = null)
    {
        if (null !== $metadata) {
            if (isset($this->metadata[$metadata])) {
                return $this->metadata[$metadata];
            }
            return $default;
        }
        return $this->metadata;
    }

    /**
     * Set metadata
     *
     * @param array $metadata
     * @return Entry
     */
    public function setMetadata($metadata, $value = null)
    {
        if (is_array($metadata) && ! empty($metadata)) {
            $this->metadata = array_merge($this->metadata, $metadata);
        } elseif (is_scalar($metadata) && ! empty($metadata)) {
            $this->metadata[$metadata] = $value;
        }
        return $this;
    }

    /**
     * Get API version
     *
     * @return int
     */
    public function getVersion()
    {
        return $this->version;
    }

    /**
     * Set API version
     *
     * Currently supported:
     *
     * - 1: entries imported from s9y
     * - 2: new entries (utilizing disqus for comments)
     *
     * @param int $value
     * @return Entry
     */
    public function setVersion($version)
    {
        $this->version = (int)$version;
        if (! in_array($this->version, [1, 2])) {
            $this->version = 2;
        }
        return $this;
    }

    /**
     * Get comments
     *
     * @return array
     */
    public function getComments()
    {
        return $this->comments;
    }

    /**
     * Set comments
     *
     * Only relevant to version 1 entries (imported from s9y).
     *
     * @param array $comments
     * @return Entry
     */
    public function setComments(array $comments)
    {
        $this->comments = $comments;
        return $this;
    }

    /**
     * Populate object from array
     *
     * @param array $array
     * @return Entry
     */
    public function fromArray(array $array)
    {
        foreach ($array as $key => $value) {
            switch ($key) {
                case 'id':
                case 'title':
                case 'body':
                case 'extended':
                case 'author':
                case 'created':
                case 'updated':
                case 'timezone':
                case 'tags':
                case 'metadata':
                case 'comments':
                case 'version':
                    $method = 'set' . ucfirst($key);
                    $this->$method($value);
                    break;
                case 'is_draft':
                    $this->setDraft($value);
                    break;
                case 'is_public':
                    $this->setPublic($value);
                    break;
                default:
                    // Unknown data is assumed to be metadata
                    $this->setMetadata($key, $value);
                    break;
            }
        }
        return $this;
    }

    /**
     * Set draft flag
     *
     * @param bool $flag
     * @return Entry
     */
    public function setDraft($flag)
    {
        $this->isDraft = (bool)$flag;
        return $this;
    }

    /**
     * Set public flag
     *
     * @param bool $flag
     * @return Entry
     */
    public function setPublic($flag)
    {
        $this->isPublic = (bool)$flag;
        return $this;
    }
}
