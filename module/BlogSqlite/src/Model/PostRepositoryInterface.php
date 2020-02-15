<?php

declare(strict_types=1);

namespace BlogSqlite\Model;

interface PostRepositoryInterface
{
    /**
     * Return a set of all blog posts that we can iterate over.
     *
     * Each entry should be a Post instance.
     *
     * @param bool $isPaginated
     * @return Post[]
     */
    public function findAllPosts($isPaginated = false);

    /**
     * Return a single blog post.
     *
     * @param  int $id Identifier of the post to return.
     * @return Post
     */
    public function findPost($id);
}
