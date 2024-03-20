<?php

if (!defined('ABSPATH')) {
     exit;
}

$main = new Main(2);

class main
{
     public $paragraphs = 0;

     public function __construct($paragraphs)
     {
          add_action('init', array($this, 'ircl_init'));
          $this->set_paragraphs($paragraphs);
     }

     // getters and setters
     public function get_paragraphs()
     {
          return $this->paragraphs;
     }

     public function set_paragraphs($paragraphs)
     {
          $this->paragraphs = $paragraphs;
     }


     public function ircl_init()
     {
          add_filter('the_content', array($this, 'ircl_insert_related_categories'));
     }

     public function ircl_insert_related_categories($content)
     {
          // get paragraph count
          $paragraphLength = $this->get_paragraphs();
          if (is_single()) {
               // explode $content into an array of paragraphs
               $content = explode('</p>', $content);

               // get categories
               $categories = get_the_category();

               // query for related posts
               $relatedPosts = new WP_Query(
                    array(
                         'category__in' => wp_get_post_categories(get_the_ID()),
                         'posts_per_page' => 3,
                         'post__not_in' => array(get_the_ID())
                    )
               );

               $content[$paragraphLength - 1] .= "<div class = 'ircl-related-links' >";
               $content[$paragraphLength - 1] .= '<ul class = "ircl-related-links-list">';
               for ($i = 0; $i < $paragraphLength; $i++) {
                    $content[$paragraphLength - 1] .= '<li>';
                    $content[$paragraphLength - 1] .= '<a href="' . get_category_link($categories[0]->term_id) . '">';
                    $content[$paragraphLength - 1] .= $relatedPosts->posts[$i]->post_title;
                    $content[$paragraphLength - 1] .= '</a>';
                    $content[$paragraphLength - 1] .= '</li>';
               }
               $content[$paragraphLength - 1] .= '</ul>';
               $content[$paragraphLength - 1] .= '</div>';


               $content = implode('</p>', $content);
               return $content;
          }
     }
}
