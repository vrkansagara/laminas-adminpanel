<?php declare(strict_types=1);

namespace PhlyBlog;

use Laminas\Stdlib\AbstractOptions;
use Laminas\Uri\UriFactory;

class CompilerOptions extends AbstractOptions
{
    protected $entryTemplate;
    protected $entryFilenameTemplate = '%s.html';
    protected $entryLinkTemplate = '/blog/%s.html';
    protected $feedHostname = 'http://localhost';
    protected $entriesTemplate;
    protected $entriesFilenameTemplate = 'blog-p%d.html';


    /* Used everywhere */
    protected $entriesUrlTemplate = '/blog-p%d.html';
    protected $entriesTitle = 'Blog Entries';
    protected $feedFilename = 'blog-%s.xml';

    /* Used for feeds */
    protected $feedBlogLink = '/blog.html';
    protected $feedFeedLink = '/blog-%s.xml';
    protected $feedTitle = 'Blog';
    protected $byYearTemplate;
    protected $byYearFilenameTemplate = 'year/%s-p%d.html';
    protected $byYearUrlTemplate = '/blog/year/%s-p%d.html';
    protected $byYearTitle = 'Blog Entries for %d';
    protected $byMonthTemplate;
    protected $byMonthFilenameTemplate = 'month/%s-p%d.html';
    protected $byMonthUrlTemplate = '/blog/month/%s-p%d.html';
    protected $byMonthTitle = 'Blog Entries for %s';
    protected $byDayTemplate;
    protected $byDayFilenameTemplate = 'day/%s-p%d.html';
    protected $byDayUrlTemplate = '/blog/day/%s-p%d.html';
    protected $byDayTitle = 'Blog Entries for %s';
    protected $byTagTemplate;
    protected $byTagFilenameTemplate = 'tag/%s-p%d.html';
    protected $byTagUrlTemplate = '/blog/tag/%s-p%d.html';
    protected $byTagTitle = 'Tag: %s';
    protected $tagFeedFilenameTemplate = 'blog/tag/%s-%s.xml';
    protected $tagFeedBlogLinkTemplate = '/blog/tag/%s.html';
    protected $tagFeedFeedLinkTemplate = '/blog/tag/%s-%s.xml';
    protected $tagFeedTitleTemplate = 'Tag: %s';
    protected $byAuthorTemplate;
    protected $byAuthorFilenameTemplate = 'author/%s-p%d.html';
    protected $byAuthorUrlTemplate = '/blog/author/%s-p%d.html';
    protected $byAuthorTitle = 'Blog Entries by %s';
    protected $authorFeedFilenameTemplate = 'blog/author/%s-%s.xml';
    protected $authorFeedBlogLinkTemplate = '/blog/author/%s.html';
    protected $authorFeedFeedLinkTemplate = '/blog/author/%s-%s.xml';
    protected $authorFeedTitleTemplate = 'Author: %s';
    protected $tagCloudUrlTemplate = '/blog/tag/%s.html';
    protected $tagCloudOptions = [];
    protected $paginatorItemCountPerPage = 10;
    protected $paginatorPageRange = 10;
    protected $feedAuthorName = '';
    protected $feedAuthorEmail = '';
    protected $feedAuthorUri = null;

    public function getEntryTemplate()
    {
        return $this->entryTemplate;
    }

    public function setEntryTemplate($entryTemplate)
    {
        $this->entryTemplate = (string)$entryTemplate;
        return $this;
    }

    public function getEntryFilenameTemplate()
    {
        return $this->entryFilenameTemplate;
    }

    public function setEntryFilenameTemplate($entryFilenameTemplate)
    {
        $this->entryFilenameTemplate = (string)$entryFilenameTemplate;
        return $this;
    }

    public function getEntryLinkTemplate()
    {
        return $this->entryLinkTemplate;
    }

    public function setEntryLinkTemplate($entryLinkTemplate)
    {
        $this->entryLinkTemplate = (string)$entryLinkTemplate;
        return $this;
    }

    public function getFeedHostname()
    {
        return $this->feedHostname;
    }

    public function setFeedHostname($feedHostname)
    {
        $this->feedHostname = (string)$feedHostname;
        return $this;
    }

    public function getEntriesFilenameTemplate()
    {
        return $this->entriesFilenameTemplate;
    }

    public function setEntriesFilenameTemplate($entriesFilenameTemplate)
    {
        $this->entriesFilenameTemplate = (string)$entriesFilenameTemplate;
        return $this;
    }

    public function getEntriesUrlTemplate()
    {
        return $this->entriesUrlTemplate;
    }

    public function setEntriesUrlTemplate($entriesUrlTemplate)
    {
        $this->entriesUrlTemplate = (string)$entriesUrlTemplate;
        return $this;
    }

    public function getEntriesTitle()
    {
        return $this->entriesTitle;
    }

    public function setEntriesTitle($entriesTitle)
    {
        $this->entriesTitle = (string)$entriesTitle;
        return $this;
    }

    public function getFeedFilename()
    {
        return $this->feedFilename;
    }

    public function setFeedFilename($feedFilename)
    {
        $this->feedFilename = (string)$feedFilename;
        return $this;
    }

    public function getFeedBlogLink()
    {
        return $this->feedBlogLink;
    }

    public function setFeedBlogLink($feedBlogLink)
    {
        $this->feedBlogLink = (string)$feedBlogLink;
        return $this;
    }

    public function getFeedFeedLink()
    {
        return $this->feedFeedLink;
    }

    public function setFeedFeedLink($feedFeedLink)
    {
        $this->feedFeedLink = (string)$feedFeedLink;
        return $this;
    }

    public function getFeedTitle()
    {
        return $this->feedTitle;
    }

    public function setFeedTitle($feedTitle)
    {
        $this->feedTitle = (string)$feedTitle;
        return $this;
    }

    public function getByYearTemplate()
    {
        $template = $this->byYearTemplate;
        if (empty($template)) {
            $template = $this->getEntriesTemplate();
        }
        return $template;
    }

    public function setByYearTemplate($byYearTemplate)
    {
        $this->byYearTemplate = (string)$byYearTemplate;
        return $this;
    }

    public function getEntriesTemplate()
    {
        return $this->entriesTemplate;
    }

    public function setEntriesTemplate($entriesTemplate)
    {
        $this->entriesTemplate = (string)$entriesTemplate;
        return $this;
    }

    public function getByYearFilenameTemplate()
    {
        return $this->byYearFilenameTemplate;
    }

    public function setByYearFilenameTemplate($byYearFilenameTemplate)
    {
        $this->byYearFilenameTemplate = (string)$byYearFilenameTemplate;
        return $this;
    }

    public function getByYearUrlTemplate()
    {
        return $this->byYearUrlTemplate;
    }

    public function setByYearUrlTemplate($byYearUrlTemplate)
    {
        $this->byYearUrlTemplate = (string)$byYearUrlTemplate;
        return $this;
    }

    public function getByYearTitle()
    {
        return $this->byYearTitle;
    }

    public function setByYearTitle($byYearTitle)
    {
        $this->byYearTitle = (string)$byYearTitle;
        return $this;
    }

    public function getByMonthTemplate()
    {
        $template = $this->byMonthTemplate;
        if (empty($template)) {
            $template = $this->getEntriesTemplate();
        }
        return $template;
    }

    public function setByMonthTemplate($byMonthTemplate)
    {
        $this->byMonthTemplate = (string)$byMonthTemplate;
        return $this;
    }

    public function getByMonthFilenameTemplate()
    {
        return $this->byMonthFilenameTemplate;
    }

    public function setByMonthFilenameTemplate($byMonthFilenameTemplate)
    {
        $this->byMonthFilenameTemplate = (string)$byMonthFilenameTemplate;
        return $this;
    }

    public function getByMonthUrlTemplate()
    {
        return $this->byMonthUrlTemplate;
    }

    public function setByMonthUrlTemplate($byMonthUrlTemplate)
    {
        $this->byMonthUrlTemplate = (string)$byMonthUrlTemplate;
        return $this;
    }

    public function getByMonthTitle()
    {
        return $this->byMonthTitle;
    }

    public function setByMonthTitle($byMonthTitle)
    {
        $this->byMonthTitle = (string)$byMonthTitle;
        return $this;
    }

    public function getByDayTemplate()
    {
        $template = $this->byDayTemplate;
        if (empty($template)) {
            $template = $this->getEntriesTemplate();
        }
        return $template;
    }

    public function setByDayTemplate($byDayTemplate)
    {
        $this->byDayTemplate = (string)$byDayTemplate;
        return $this;
    }

    public function getByDayFilenameTemplate()
    {
        return $this->byDayFilenameTemplate;
    }

    public function setByDayFilenameTemplate($byDayFilenameTemplate)
    {
        $this->byDayFilenameTemplate = (string)$byDayFilenameTemplate;
        return $this;
    }

    public function getByDayUrlTemplate()
    {
        return $this->byDayUrlTemplate;
    }

    public function setByDayUrlTemplate($byDayUrlTemplate)
    {
        $this->byDayUrlTemplate = (string)$byDayUrlTemplate;
        return $this;
    }

    public function getByDayTitle()
    {
        return $this->byDayTitle;
    }

    public function setByDayTitle($byDayTitle)
    {
        $this->byDayTitle = (string)$byDayTitle;
        return $this;
    }

    public function getByTagTemplate()
    {
        $template = $this->byTagTemplate;
        if (empty($template)) {
            $template = $this->getEntriesTemplate();
        }
        return $template;
    }

    public function setByTagTemplate($byTagTemplate)
    {
        $this->byTagTemplate = (string)$byTagTemplate;
        return $this;
    }

    public function getByTagFilenameTemplate()
    {
        return $this->byTagFilenameTemplate;
    }

    public function setByTagFilenameTemplate($byTagFilenameTemplate)
    {
        $this->byTagFilenameTemplate = (string)$byTagFilenameTemplate;
        return $this;
    }

    public function getByTagUrlTemplate()
    {
        return $this->byTagUrlTemplate;
    }

    public function setByTagUrlTemplate($byTagUrlTemplate)
    {
        $this->byTagUrlTemplate = (string)$byTagUrlTemplate;
        return $this;
    }

    public function getByTagTitle()
    {
        return $this->byTagTitle;
    }

    public function setByTagTitle($byTagTitle)
    {
        $this->byTagTitle = (string)$byTagTitle;
        return $this;
    }

    public function getTagFeedFilenameTemplate()
    {
        return $this->tagFeedFilenameTemplate;
    }

    public function setTagFeedFilenameTemplate($tagFeedFilenameTemplate)
    {
        $this->tagFeedFilenameTemplate = (string)$tagFeedFilenameTemplate;
        return $this;
    }

    public function getTagFeedBlogLinkTemplate()
    {
        return $this->tagFeedBlogLinkTemplate;
    }

    public function setTagFeedBlogLinkTemplate($tagFeedBlogLinkTemplate)
    {
        $this->tagFeedBlogLinkTemplate = (string)$tagFeedBlogLinkTemplate;
        return $this;
    }

    public function getTagFeedFeedLinkTemplate()
    {
        return $this->tagFeedFeedLinkTemplate;
    }

    public function setTagFeedFeedLinkTemplate($tagFeedFeedLinkTemplate)
    {
        $this->tagFeedFeedLinkTemplate = (string)$tagFeedFeedLinkTemplate;
        return $this;
    }

    public function getTagFeedTitleTemplate()
    {
        return $this->tagFeedTitleTemplate;
    }

    public function setTagFeedTitleTemplate($tagFeedTitleTemplate)
    {
        $this->tagFeedTitleTemplate = (string)$tagFeedTitleTemplate;
        return $this;
    }

    public function getByAuthorTemplate()
    {
        $template = $this->byAuthorTemplate;
        if (empty($template)) {
            $template = $this->getEntriesTemplate();
        }
        return $template;
    }

    public function setByAuthorTemplate($byAuthorTemplate)
    {
        $this->byAuthorTemplate = (string)$byAuthorTemplate;
        return $this;
    }

    public function getByAuthorFilenameTemplate()
    {
        return $this->byAuthorFilenameTemplate;
    }

    public function setByAuthorFilenameTemplate($byAuthorFilenameTemplate)
    {
        $this->byAuthorFilenameTemplate = (string)$byAuthorFilenameTemplate;
        return $this;
    }

    public function getByAuthorUrlTemplate()
    {
        return $this->byAuthorUrlTemplate;
    }

    public function setByAuthorUrlTemplate($byAuthorUrlTemplate)
    {
        $this->byAuthorUrlTemplate = (string)$byAuthorUrlTemplate;
        return $this;
    }

    public function getByAuthorTitle()
    {
        return $this->byAuthorTitle;
    }

    public function setByAuthorTitle($byAuthorTitle)
    {
        $this->byAuthorTitle = (string)$byAuthorTitle;
        return $this;
    }

    public function getAuthorFeedFilenameTemplate()
    {
        return $this->authorFeedFilenameTemplate;
    }

    public function setAuthorFeedFilenameTemplate($authorFeedFilenameTemplate)
    {
        $this->authorFeedFilenameTemplate = (string)$authorFeedFilenameTemplate;
        return $this;
    }

    public function getAuthorFeedBlogLinkTemplate()
    {
        return $this->authorFeedBlogLinkTemplate;
    }

    public function setAuthorFeedBlogLinkTemplate($authorFeedBlogLinkTemplate)
    {
        $this->authorFeedBlogLinkTemplate = (string)$authorFeedBlogLinkTemplate;
        return $this;
    }

    public function getAuthorFeedFeedLinkTemplate()
    {
        return $this->authorFeedFeedLinkTemplate;
    }

    public function setAuthorFeedFeedLinkTemplate($authorFeedFeedLinkTemplate)
    {
        $this->authorFeedFeedLinkTemplate = (string)$authorFeedFeedLinkTemplate;
        return $this;
    }

    public function getAuthorFeedTitleTemplate()
    {
        return $this->authorFeedTitleTemplate;
    }

    public function setAuthorFeedTitleTemplate($authorFeedTitleTemplate)
    {
        $this->authorFeedTitleTemplate = (string)$authorFeedTitleTemplate;
        return $this;
    }

    public function getTagCloudUrlTemplate()
    {
        return $this->tagCloudUrlTemplate;
    }

    public function setTagCloudUrlTemplate($tagCloudUrlTemplate)
    {
        $this->tagCloudUrlTemplate = (string)$tagCloudUrlTemplate;
        return $this;
    }

    public function getTagCloudOptions()
    {
        return $this->tagCloudOptions;
    }

    public function setTagCloudOptions(array $tagCloudOptions)
    {
        $this->tagCloudOptions = $tagCloudOptions;
        return $this;
    }

    public function getPaginatorItemCountPerPage()
    {
        return $this->paginatorItemCountPerPage;
    }

    public function setPaginatorItemCountPerPage($paginatorItemCountPerPage)
    {
        $paginatorItemCountPerPage = (int)$paginatorItemCountPerPage;
        if ($paginatorItemCountPerPage < 1) {
            throw new \InvalidArgumentException('Paginator item count per page must be at least 1');
        }
        $this->paginatorItemCountPerPage = (int)$paginatorItemCountPerPage;
        return $this;
    }

    public function getPaginatorPageRange()
    {
        return $this->paginatorPageRange;
    }

    public function setPaginatorPageRange($paginatorPageRange)
    {
        $paginatorPageRange = (int)$paginatorPageRange;
        if ($paginatorPageRange < 2) {
            throw new \InvalidArgumentException('Paginator page range must be >= 2');
        }
        $this->paginatorPageRange = (int)$paginatorPageRange;
        return $this;
    }

    public function getFeedAuthorName()
    {
        return $this->feedAuthorName;
    }

    public function setFeedAuthorName($feedAuthorName)
    {
        $this->feedAuthorName = (string)$feedAuthorName;
        return $this;
    }

    public function getFeedAuthorEmail()
    {
        return $this->feedAuthorEmail;
    }

    public function setFeedAuthorEmail($feedAuthorEmail)
    {
        $this->feedAuthorEmail = (string)$feedAuthorEmail;
        return $this;
    }

    public function getFeedAuthorUri()
    {
        return $this->feedAuthorUri;
    }

    public function setFeedAuthorUri($feedAuthorUri)
    {
        $uri = UriFactory::factory($feedAuthorUri);
        if (! $uri->isValid()) {
            throw new \InvalidArgumentException('Author URI for feed is invalid');
        }
        $this->feedAuthorUri = $feedAuthorUri;
        return $this;
    }
}
