<?php

/**
 * The Settings API
 */

/*
For the future

add_options_page - > Callback function 
*/
$ircl_settings = new ircl_settings();
class ircl_settings
{

     public function __construct()
     {
          add_action("admin_menu", array($this, "admin_page"));
          add_action("admin_init", array($this, "settings"));
     }
     // 1 - Add a new settings page
     // This will add a new settings page to the admin menu
     public function admin_page()
     {
          add_menu_page(
               "WP Insert Related Categories Links",
               "Related Links",
               "manage_options",
               "ircl-settings", // Slug
               array($this, "settings_page"), // Callback function settings_page
               "dashicons-admin-links",
          );
     }
     // 2 - Add a new settings page
     // This will add a new settings page to the admin menu
     // This function will be called when the settings page is loaded
     function settings_page()
     {
?>
          <div class="wrap">
               <h2>WP Insert Related Categories Links</h2>
               <form method="post" action="options.php">
                    <?php settings_fields("ircl-settings-group"); ?>
                    <?php do_settings_sections("ircl-settings"); ?>
                    <?php submit_button(); ?>
               </form>
          </div>
<?php
     }
     // 3 - Add a new settings page
     function settings()
     {
          //3.a
          add_settings_section(
               "ircl-settings-section", // ID
               "Set this to ON", // Title
               null, // Callback
               "ircl-settings" // Page slug
          );

          // 3.b - Add a new section to the settings page
          add_settings_field(
               "ircl-is-active", // ID->
               "Is Active?",  // Title 
               array($this, "is_active_HTML"), // Callback HTML
               "ircl-settings", // Page slug
               "ircl-settings-section" // Section
          );
          //3.c - Register a new setting in DB
          register_setting(
               "ircl-settings-group",
               "ircl-is-active", // ID->
               array(
                    'sanatize_callback' => "sanitize_bool_field", // Sanitize
                    'default' => '2',
               )
          );
          
          // A number filed Set number of paragraph
          add_settings_field(
               "ircl-number-paragraph", // ID
               "Number of Paragraphs", // Title
               array($this, "number_paragraph_HTML"), // Callback
               "ircl-settings", // Page slug
               "ircl-settings-section" // Section
          );
          register_setting(
               "ircl-settings-group",
               "ircl-number-paragraph", // ID
               array(
                    'sanatize_callback' => "sanitize_text_field",
                    'default' => '2',
               )
          );

          //Boolean is last paragraph
          add_settings_field(
               "ircl-is-last-paragraph", // ID
               "Is Last Paragraph?", // Title
               array($this, "is_last_paragraph_HTML"), // Callback
               "ircl-settings", // Page slug
               "ircl-settings-section" // Section
          );

          register_setting(
               "ircl-settings-group",
               "ircl-is-last-paragraph", // ID
               array(
                    'sanatize_callback' => "sanitize_bool_field", // Sanitize
                    'default' => '1',
               )
          );

          
     }
     // Is active BOOLEAN
     function is_active_HTML()
     { ?>
        <input type='checkbox' name='ircl-is-active' value='1' <?php checked(1, get_option('ircl-is-active'), true); ?> />
     <?php 
     }
     //Number of paragraphs
     function number_paragraph_HTML()
     { ?>
          <input type="number" name="ircl-number-paragraph" value="<?php echo get_option('ircl-number-paragraph'); ?>" size="4"/> <br>
          <small>It will display the links after this number of paragraphs</small> 
     <?php }

     //Is last paragraph BOOLEAN
     function is_last_paragraph_HTML()
     { ?>
          <input type='checkbox' name='ircl-is-last-paragraph' value='1' <?php checked(1, get_option('ircl-is-last-paragraph'), true);  ?>  /> <br>
          <small>It will display the links after the last paragraph.</small>     
     <?php }

     // Sanitize boolean

     function sanitize_bool_field($input)
     {
          return (isset($input) && $input == 1) ? 1 : 0;
     }






    
}
