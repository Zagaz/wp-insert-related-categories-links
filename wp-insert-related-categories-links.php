<?php 

class main{
     public function __construct(){
          add_filter('the_content', array($this, 'insert_related_categories_links'));
     }
     
     public function insert_related_categories_links($content){
          if(is_single()){
               $categories = get_the_category();
               $links = '';
               foreach($categories as $category){
                    $links .= '<a href="'.get_category_link($category->term_id).'">'.$category->name.'</a>, ';
               }
               $links = rtrim($links, ', ');
               $content .= '<p>Related Categories: '.$links.'</p>';
          }
          return $content;
     }
}

