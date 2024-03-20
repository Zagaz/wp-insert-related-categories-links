<?php

/**
 * This is the main file for the plugin. It will be responsible for the main functionality of the plugin.
 * 
 * @package IRCL
 * @version 1.0
 * @return void
 * 
 */


if (!defined('ABSPATH')) {
     exit;
}

$ircl_is_active = get_option('ircl-is-active'); // Bool
$ircl_number_paragraph = get_option('ircl-number-paragraph'); // Int
$ircl_is_last_paragraph = get_option('ircl-is-last-paragraph'); // Bool

$main = new Main( $ircl_is_active, $ircl_number_paragraph, $ircl_is_last_paragraph);

/**
 * Main class for the plugin
 */
class main
{
     public $is_active = false; 
     public $paragraphs = 0;
     public $is_last = false;

     public function __construct($is_active , $paragraphs, $is_last = false)
     {
          add_action('init', array($this, 'ircl_content'));
          $this-> set_is_active($is_active); 
          $this->set_paragraphs($paragraphs);
          $this->set_is_last($is_last);

     }

     // getters and setters

     public function get_is_active()
     {
          return $this->is_active;
     }

     public function set_is_active($is_active)
     {
          $this->is_active = $is_active;
     }

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

          $the_paragraph_location = $this->get_paragraphs() - 1; // E.g.: If 3 it will be displayed after 3rd paragraph.
          $content = explode('</p>', $content);

          // Placing the related links where it should be
          if ($this->get_is_last()) {
               $the_paragraph_location = count($content) -1;
          } else {
               $the_paragraph_location = $the_paragraph_location;
          }

               
          if (is_single() ) {
              if ($this->get_is_active() == "") {
                       return implode('</p>', $content);
              }
              

               $relatedPosts = new WP_Query(
                    array(
                         'category__in' => wp_get_post_categories(get_the_ID()),
                         'posts_per_page' => 3,
                         'post__not_in' => array(get_the_ID())
                    )
               );

               $content[$the_paragraph_location] .= "<div class = 'ircl-related-links' >";
               $content[$the_paragraph_location] .= '<ul class = "ircl-related-links-list">';
               foreach ($relatedPosts->posts as $post) {
                    $content[$the_paragraph_location] .= '<li>';
                    $content[$the_paragraph_location] .= '<a href="' . get_permalink($post->ID) . '">';
                    $content[$the_paragraph_location] .= $post->post_title;
                    $content[$the_paragraph_location] .= '</a>';
                    $content[$the_paragraph_location] .= '</li>';
               }
               $content[$the_paragraph_location] .= '</ul>';
               $content[$the_paragraph_location] .= '</div>';

               return implode('</p>', $content);
          }
     }
}
