<?php

namespace Mpcs\Article\Seeds;

use Illuminate\Database\Seeder;

use Mpcs\Core\Models\Permission;
use Mpcs\Article\Models\ArticleCategory;

class ArticleInstallSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {

        // 권한 생성
        Permission::insertOrIgnore([
            [
                'name'        => 'Article-Manage',
                'slug'        => 'article.manage',
                'description' => 'Article 관리',
                'is_visible'  => 1,
                'created_at'  => date("Y-m-d H:i:s"),
                'updated_at'  => date("Y-m-d H:i:s"),
            ],
            [
                'name'        => 'Article-List',
                'slug'        => 'article.list',
                'description' => 'Article 목록',
                'is_visible'  => 1,
                'created_at'  => date("Y-m-d H:i:s"),
                'updated_at'  => date("Y-m-d H:i:s"),
            ],
            [
                'name'        => 'Article-View',
                'slug'        => 'article.view',
                'description' => 'Article 보기',
                'is_visible'  => 1,
                'created_at'  => date("Y-m-d H:i:s"),
                'updated_at'  => date("Y-m-d H:i:s"),
            ],
            [
                'name'        => 'Article-Edit',
                'slug'        => 'article.edit',
                'description' => 'Article 편집',
                'is_visible'  => 1,
                'created_at'  => date("Y-m-d H:i:s"),
                'updated_at'  => date("Y-m-d H:i:s"),
            ],
            [
                'name'        => 'Article-Create',
                'slug'        => 'article.create',
                'description' => 'Article 생성',
                'is_visible'  => 1,
                'created_at'  => date("Y-m-d H:i:s"),
                'updated_at'  => date("Y-m-d H:i:s"),
            ],
            [
                'name'        => 'Article-Delete',
                'slug'        => 'article.delete',
                'description' => 'Article 삭제',
                'is_visible'  => 1,
                'created_at'  => date("Y-m-d H:i:s"),
                'updated_at'  => date("Y-m-d H:i:s"),
            ],
            [
                'name'        => 'Article-Category-Manage',
                'slug'        => 'article.category.manage',
                'description' => 'Article 관리',
                'is_visible'  => 1,
                'created_at'  => date("Y-m-d H:i:s"),
                'updated_at'  => date("Y-m-d H:i:s"),
            ],
            [
                'name'        => 'Article-Category-List',
                'slug'        => 'article.category.list',
                'description' => 'Article 목록',
                'is_visible'  => 1,
                'created_at'  => date("Y-m-d H:i:s"),
                'updated_at'  => date("Y-m-d H:i:s"),
            ],
            [
                'name'        => 'Article-Category-View',
                'slug'        => 'article.category.view',
                'description' => 'Article 보기',
                'is_visible'  => 1,
                'created_at'  => date("Y-m-d H:i:s"),
                'updated_at'  => date("Y-m-d H:i:s"),
            ],
            [
                'name'        => 'Article-Category-Edit',
                'slug'        => 'article.category.edit',
                'description' => 'Article 편집',
                'is_visible'  => 1,
                'created_at'  => date("Y-m-d H:i:s"),
                'updated_at'  => date("Y-m-d H:i:s"),
            ],
            [
                'name'        => 'Article-Category-Create',
                'slug'        => 'article.category.create',
                'description' => 'Article 생성',
                'is_visible'  => 1,
                'created_at'  => date("Y-m-d H:i:s"),
                'updated_at'  => date("Y-m-d H:i:s"),
            ],
            [
                'name'        => 'Article-Category-Delete',
                'slug'        => 'article.category.delete',
                'description' => 'Article 삭제',
                'is_visible'  => 1,
                'created_at'  => date("Y-m-d H:i:s"),
                'updated_at'  => date("Y-m-d H:i:s"),
            ],
        ]);

        // 카테고리 생성
        if (config('mpcs.project_type') == 'cms') {
            $categories = [
                [
                    'order'       => 1,
                    'parent_id'   => null,
                    'name'        => 'Notice',
                    'slug'        => 'Notice',
                    'description' => 'Notice',
                    'type'        => 2,
                    'is_visible'  => 1,
                    'depth'       => 1,
                    'deleted_at'  => null,
                ],
                [
                    'order'       => 2,
                    'parent_id'   => null,
                    'name'        => 'Faq',
                    'slug'        => 'Faq',
                    'description' => 'Faq',
                    'type'        => 2,
                    'is_visible'  => 1,
                    'depth'       => 1,
                    'deleted_at'  => null,
                ],
                [
                    'order'       => 3,
                    'parent_id'   => null,
                    'name'        => 'QA',
                    'slug'        => 'QA',
                    'description' => 'QA',
                    'type'        => 2,
                    'is_visible'  => 1,
                    'depth'       => 1,
                    'deleted_at'  => null,
                ]
            ];
            // website
        } else {
            $categories = [
                [
                    'order'       => 1,
                    'parent_id'   => null,
                    'name'        => 'Pages',
                    'slug'        => 'Pages',
                    'description' => 'Web pages',
                    'type'        => 4,
                    'is_visible'  => 1,
                    'depth'       => 1,
                    'deleted_at'  => null,
                ],
                [
                    'order'       => 2,
                    'parent_id'   => null,
                    'name'        => 'Community',
                    'slug'        => 'Community',
                    'description' => 'Community',
                    'type'        => 2,
                    'is_visible'  => 1,
                    'depth'       => 1,
                    'deleted_at'  => null,
                ],
                [
                    'order'       => 1,
                    'parent_id'   => 2,
                    'name'        => 'Notice',
                    'slug'        => 'Notice',
                    'description' => 'Notice',
                    'type'        => 2,
                    'is_visible'  => 1,
                    'depth'       => 2,
                    'deleted_at'  => null,
                ],
                [
                    'order'       => 2,
                    'parent_id'   => 2,
                    'name'        => 'Faq',
                    'slug'        => 'Faq',
                    'description' => 'Faq',
                    'type'        => 2,
                    'is_visible'  => 1,
                    'depth'       => 2,
                    'deleted_at'  => null,
                ],
                [
                    'order'       => 3,
                    'parent_id'   => 2,
                    'name'        => 'QA',
                    'slug'        => 'QA',
                    'description' => 'QA',
                    'type'        => 2,
                    'is_visible'  => 1,
                    'depth'       => 2,
                    'deleted_at'  => null,
                ],
                [
                    'order'       => 4,
                    'parent_id'   => 2,
                    'name'        => 'Gallery',
                    'slug'        => 'Gallery',
                    'description' => 'Gallery',
                    'type'        => 6,
                    'is_visible'  => 1,
                    'depth'       => 2,
                    'deleted_at'  => null,
                ],
                [
                    'order'       => 4,
                    'parent_id'   => null,
                    'name'        => 'History',
                    'slug'        => 'History',
                    'description' => 'History pages',
                    'type'        => 4,
                    'is_visible'  => 1,
                    'depth'       => 1,
                    'deleted_at'  => null,
                ],
                [
                    'order'       => 5,
                    'parent_id'   => null,
                    'name'        => 'Webzine',
                    'slug'        => 'Webzine',
                    'description' => 'Webzine pages',
                    'type'        => 4,
                    'is_visible'  => 1,
                    'depth'       => 1,
                    'deleted_at'  => null,
                ],
            ];
        }
        ArticleCategory::insertOrIgnore($categories);
    }
}
