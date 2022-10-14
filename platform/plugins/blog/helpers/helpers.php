<?php

use Botble\Base\Enums\BaseStatusEnum;
use Botble\Base\Models\BaseModel;
use Botble\Base\Supports\SortItemsWithChildrenHelper;
use Botble\Blog\Repositories\Interfaces\CategoryInterface;
use Botble\Blog\Repositories\Interfaces\PostInterface;
use Botble\Blog\Repositories\Interfaces\TagInterface;
use Botble\Blog\Supports\PostFormat;
use Illuminate\Support\Arr;
use Illuminate\Support\Collection;

if (!function_exists('get_featured_posts')) {
    /**
     * @param int $limit
     * @param array $with
     * @return Collection
     */
    function get_featured_posts(int $limit, array $with = []): Collection
    {
        return app(PostInterface::class)->getFeatured($limit, $with);
    }
}

if (!function_exists('get_latest_posts')) {
    /**
     * @param int $limit
     * @param array $excepts
     * @param array $with
     * @return Collection
     */
    function get_latest_posts(int $limit, array $excepts = [], array $with = []): Collection
    {
        return app(PostInterface::class)->getListPostNonInList($excepts, $limit, $with);
    }
}

if (!function_exists('get_related_posts')) {
    /**
     * @param int $id
     * @param int $limit
     * @return Collection
     */
    function get_related_posts(int $id, int $limit): Collection
    {
        return app(PostInterface::class)->getRelated($id, $limit);
    }
}

if (!function_exists('get_posts_by_category')) {
    /**
     * @param int $categoryId
     * @param int $paginate
     * @param int $limit
     * @return Collection
     */
    function get_posts_by_category(int $categoryId, int $paginate = 12, int $limit = 0)
    {
        return app(PostInterface::class)->getByCategory($categoryId, $paginate, $limit);
    }
}

if (!function_exists('get_posts_by_tag')) {
    /**
     * @param string $slug
     * @param int $paginate
     * @return Collection
     */
    function get_posts_by_tag(string $slug, int $paginate = 12)
    {
        return app(PostInterface::class)->getByTag($slug, $paginate);
    }
}

if (!function_exists('get_posts_by_user')) {
    /**
     * @param int $authorId
     * @param int $paginate
     * @return Collection
     */
    function get_posts_by_user(int $authorId, int $paginate = 12)
    {
        return app(PostInterface::class)->getByUserId($authorId, $paginate);
    }
}

if (!function_exists('get_all_posts')) {
    /**
     * @param boolean $active
     * @param int $perPage
     * @param array $with
     * @return Collection
     */
    function get_all_posts(
        bool  $active = true,
        int   $perPage = 12,
        array $with = ['slugable', 'categories', 'categories.slugable', 'author']
    ) {
        return app(PostInterface::class)->getAllPosts($perPage, $active, $with);
    }
}

if (!function_exists('get_recent_posts')) {
    /**
     * @param int $limit
     * @return Collection
     */
    function get_recent_posts(int $limit)
    {
        return app(PostInterface::class)->getRecentPosts($limit);
    }
}

if (!function_exists('get_featured_categories')) {
    /**
     * @param int $limit
     * @param array $with
     * @return Collection
     */
    function get_featured_categories(int $limit, array $with = [])
    {
        return app(CategoryInterface::class)->getFeaturedCategories($limit, $with);
    }
}

if (!function_exists('get_all_categories')) {
    /**
     * @param array $condition
     * @param array $with
     * @return Collection
     */
    function get_all_categories(array $condition = [], array $with = [])
    {
        return app(CategoryInterface::class)->getAllCategories($condition, $with);
    }
}

if (!function_exists('get_all_tags')) {
    /**
     * @param boolean $active
     * @return Collection
     */
    function get_all_tags(bool $active = true)
    {
        return app(TagInterface::class)->getAllTags($active);
    }
}

if (!function_exists('get_popular_tags')) {
    /**
     * @param int $limit
     * @param array|string[] $with
     * @param array $withCount
     * @return Collection
     */
    function get_popular_tags(int $limit = 10, array $with = ['slugable'], array $withCount = ['posts'])
    {
        return app(TagInterface::class)->getPopularTags($limit, $with, $withCount);
    }
}

if (!function_exists('get_popular_posts')) {
    /**
     * @param integer $limit
     * @param array $args
     * @return Collection
     */
    function get_popular_posts(int $limit = 10, array $args = [])
    {
        return app(PostInterface::class)->getPopularPosts($limit, $args);
    }
}

if (!function_exists('get_popular_categories')) {
    /**
     * @param integer $limit
     * @param array $with
     * @param array $withCount
     * @return Collection
     */
    function get_popular_categories(int $limit = 10, array $with = ['slugable'], array $withCount = ['posts'])
    {
        return app(CategoryInterface::class)->getPopularCategories($limit, $with, $withCount);
    }
}

if (!function_exists('get_category_by_id')) {
    /**
     * @param integer $id
     * @return BaseModel
     */
    function get_category_by_id(int $id): BaseModel
    {
        return app(CategoryInterface::class)->getCategoryById($id);
    }
}

if (!function_exists('get_categories')) {
    /**
     * @param array $args
     * @return array
     */
    function get_categories(array $args = []): array
    {
        $indent = Arr::get($args, 'indent', '——');

        $repo = app(CategoryInterface::class);

        $categories = $repo->getCategories(Arr::get($args, 'select', ['*']), [
            'created_at' => 'DESC',
            'is_default' => 'DESC',
            'order'      => 'ASC',
        ]);

        $categories = sort_item_with_children($categories);

        foreach ($categories as $category) {
            $depth = (int)$category->depth;
            $indentText = str_repeat($indent, $depth);
            $category->indent_text = $indentText;
        }

        return $categories;
    }
}

if (!function_exists('get_categories_with_children')) {
    /**
     * @return Collection
     * @throws Exception
     */
    function get_categories_with_children()
    {
        $categories = app(CategoryInterface::class)
            ->getAllCategoriesWithChildren(['status' => BaseStatusEnum::PUBLISHED], [], ['id', 'name', 'parent_id']);

        return app(SortItemsWithChildrenHelper::class)
            ->setChildrenProperty('child_cats')
            ->setItems($categories)
            ->sort();
    }
}

if (!function_exists('register_post_format')) {
    /**
     * @param array $formats
     * @return void
     */
    function register_post_format(array $formats)
    {
        PostFormat::registerPostFormat($formats);
    }
}

if (!function_exists('get_post_formats')) {
    /**
     * @param bool $convertToList
     * @return array
     */
    function get_post_formats(bool $convertToList = false): array
    {
        return PostFormat::getPostFormats($convertToList);
    }
}

if (!function_exists('get_blog_page_id')) {
    /**
     * @return int
     */
    function get_blog_page_id(): int
    {
        return (int)theme_option('blog_page_id', setting('blog_page_id'));
    }
}

if (!function_exists('get_blog_page_url')) {
    /**
     * @return string
     */
    function get_blog_page_url(): string
    {
        $blogPageId = (int)theme_option('blog_page_id', setting('blog_page_id'));

        if (!$blogPageId) {
            return url('/');
        }

        $blogPage = app(\Botble\Page\Repositories\Interfaces\PageInterface::class)->findById($blogPageId);

        if (!$blogPage) {
            return url('/');
        }

        return $blogPage->url;
    }
}
