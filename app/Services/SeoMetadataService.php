<?php
namespace App\Services;

use App\Models\SeoMetadata;

class SeoMetadataService
{
    public function generateForProduct($product, $category, $subCategory, $tags, $variants)
    {
        try {
            // Generate meta title
            $metaTitle = $product->name;
            if (!empty($category)) {
                $metaTitle .= ' - ' . $category;
            }
            if (!empty($subCategory) || $subCategory == null) {
                $metaTitle .= ' - ' . $subCategory;
            }

            // Generate meta keywords
            $metaKeywords = [$product->name];
            if (!empty($category)) {
                $metaKeywords[] = $category;
            }
            if (!empty($subCategory) || $subCategory == null)  {
                $metaKeywords[] = $subCategory;
            }
            if (!empty($tags)) {
                foreach ($tags as $tag) {
                    $metaKeywords[] = $tag->name;
                }
            }
            if (!empty($variants)) {
                foreach ($variants as $variant) {
                    $metaKeywords[] = $variant['sku'];
                }
            }
            $metaKeywordString = implode(', ', $metaKeywords);

            // Generate meta description
            $metaDescription = $product->description;
            if (strlen($metaDescription) > 160) {
                $metaDescription = substr($metaDescription, 0, 157) . '...';
            }

            // Create or update SeoMetadata
            SeoMetadata::updateOrCreate(
                [
                    'entity_type' => 'product',
                    'entity_id' => $product->id
                ],
                [
                    'meta_title' => $metaTitle,
                    'meta_keyword' => $metaKeywordString,
                    'meta_description' => $metaDescription
                ]
            );
        } catch (\Exception $e) {
            // Handle exception
        }

    }

    public function generateForCategory($category)
    {
        try {
            // Generate meta title
            $metaTitle = $category->name;

            // Generate meta keywords
            $metaKeywords = [$category->name];
            $metaKeywordString = implode(', ', $metaKeywords);

            // Generate meta description
            $metaDescription = $category->description;
            if (strlen($metaDescription) > 160) {
                $metaDescription = substr($metaDescription, 0, 157) . '...';
            }

            // Create or update SeoMetadata
            SeoMetadata::updateOrCreate(
                [
                    'entity_type' => 'category',
                    'entity_id' => $category->id
                ],
                [
                    'meta_title' => $metaTitle,
                    'meta_keyword' => $metaKeywordString,
                    'meta_description' => $metaDescription
                ]
            );
        } catch (\Exception $e) {
            // Handle exception
        }
    }

    // generateForSubCategory 
    public function generateForSubCategory($subCategory)
    {
        try {
            // Generate meta title
            $metaTitle = $subCategory->name;

            // Generate meta keywords
            $metaKeywords = [$subCategory->name];
            $metaKeywordString = implode(', ', $metaKeywords);

            // Generate meta description
            $metaDescription = $subCategory->description;
            if (strlen($metaDescription) > 160) {
                $metaDescription = substr($metaDescription, 0, 157) . '...';
            }

            // Create or update SeoMetadata
            SeoMetadata::updateOrCreate(
                [
                    'entity_type' => 'sub_category',
                    'entity_id' => $subCategory->id
                ],
                [
                    'meta_title' => $metaTitle,
                    'meta_keyword' => $metaKeywordString,
                    'meta_description' => $metaDescription
                ]
            );
        } catch (\Exception $e) {
            // Handle exception
        }
    }
}