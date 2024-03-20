<?php

if (!defined('ABSPATH')) {
     exit;
}

$main = new Main(2, false); // This will be changed to the admin settings

class main
{
     public $paragraphs = 0;
     public $is_last = false;

     public function __construct($paragraphs, $is_last = false)
     {
          add_action('init', array($this, 'ircl_content'));
          $this->set_paragraphs($paragraphs);
          $this->set_is_last($is_last);
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

     public function get_is_last()
     {
          return $this->is_last;
     }

     public function set_is_last($is_last)
     {
          $this->is_last = $is_last;
     }


     public function ircl_content()
     {
          add_filter('the_content', array($this, 'ircl_insert_related_categories'));
     }

     public function ircl_insert_related_categories($content)
     {

          $the_paragraph_location = $this->get_paragraphs();
          $content = explode('</p>', $content);

          if ($this->get_is_last()) {
               $the_paragraph_location = count($content);
          } else {
               $the_paragraph_location = $the_paragraph_location;
          }

          // A fallback in case the paragraph location is greater than the content
          if (count($content) < $the_paragraph_location) {
               $the_paragraph_location = count($content);
          }


          if (is_single()) {

          
               // query for related posts
               $relatedPosts = new WP_Query(
                    array(
                         'category__in' => wp_get_post_categories(get_the_ID()),
                         'posts_per_page' => 3,
                         'post__not_in' => array(get_the_ID())
                    )
               );

               $content[$the_paragraph_location - 1] .= "<div class = 'ircl-related-links' >";
               $content[$the_paragraph_location - 1] .= '<ul class = "ircl-related-links-list">';
               foreach ($relatedPosts->posts as $post) {
                    $content[$the_paragraph_location - 1] .= '<li>';
                    $content[$the_paragraph_location - 1] .= '<a href="' . get_permalink($post->ID) . '">';
                    $content[$the_paragraph_location - 1] .= $post->post_title;
                    $content[$the_paragraph_location - 1] .= '</a>';
                    $content[$the_paragraph_location - 1] .= '</li>';
               }
               $content[$the_paragraph_location - 1] .= '</ul>';
               $content[$the_paragraph_location - 1] .= '</div>';


               $content = implode('</p>', $content);
               return $content;
          }
     }
}
