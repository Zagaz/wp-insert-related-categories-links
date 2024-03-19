<?php 

if ( ! defined ( 'ABSPATH' ) ) {
    exit;
}

$main = new Main();

class main{
     public function __construct(){
          add_action( 'init', array( $this, 'ircl_init' ) );
     }
     
     public function ircl_init(){
          add_filter( 'the_content', array( $this, 'ircl_insert_related_categories' ) );
     }
     
     public function ircl_insert_related_categories( $content ){
          if ( is_single() ) {
               $categories = get_the_category();
               $categories_links = array();
               foreach ( $categories as $category ) {
                    $categories_links[] = '<a href="' . get_category_link( $category->term_id ) . '">' . $category->name . '</a>';
               }
               $categories_links = implode( ', ', $categories_links );
               $content .= '<p>Related categories: ' . $categories_links . '</p>';
          }
          return $content;
     }
}

