<?php
/**
 * Contact form widget
 *
 * @package WordPress
 * @subpackage Zefir
 * @since Zefir 1.0
 */

/**
 * Contact Form Widget class
 */
class BIRD_Mini_Contact_Form extends WP_Widget {

  function BIRD_Mini_Contact_Form() {
    $widget_ops = array('classname' => 'bird_mini_contact_form_widget', 'description' => __('Widget shows the contact form.', 'birdwp-theme'));
    $this->WP_Widget('bird_mini_contact_form_widget', __('Zefir: Contact form', 'birdwp-theme'), $widget_ops);
  }

  function widget($args, $instance) {
    extract($args);
	
    $title = $instance['title'];
    $email = $instance['email'];
    if (!is_email($email)) {
      $email = '';
    }

    echo $before_widget;
    if ($title) {
      echo $before_title.$title.$after_title;
    } ?>

    <form name="contact" id="mini-contact" class="mini-contact-form clearfix" action="" method="post">
      <table width="100%" border="0" cellspacing="0" cellpadding="0">
        <tr>
          <td class="label-field"><label for="mini-contact-name"><?php esc_html_e('Name:', 'birdwp-theme'); ?></label></td>
        </tr>
        <tr>
          <td class="input-field"><input type="text" id="mini-contact-name" class="form-input" placeholder=""></td>
        </tr>
        <tr>
          <td class="label-field"><label for="mini-contact-email"><?php esc_html_e('Email:', 'birdwp-theme'); ?></label></td>
        </tr>
        <tr>
          <td class="input-field"><input type="text" id="mini-contact-email" class="form-input" placeholder=""></td>
        </tr>
        <tr>
          <td class="label-field"><label for="mini-contact-message"><?php esc_html_e('Message:', 'birdwp-theme'); ?></label></td>
        </tr>
        <tr>
          <td class="input-field"><textarea id="mini-contact-message" class="form-textarea" cols="" rows=""></textarea></td>
        </tr>
        <tr>
          <td class="submit-field">
            <input type="submit" id="mini-contact-submit" class="form-submit-small" value="Send message">
            <input type="hidden" id="admin-email" value="<?php echo esc_attr($email); ?>">
            <span id="contact-loader"></span>
            <span id="contact-result"></span>
          </td>
        </tr>
      </table>
    </form>

    <script>
    jQuery.noConflict()(function($){
      $(document).ready(function() {
        "use strict";

        // check form
        function checkForm(nameVal, emailVal, msgVal) {
          var
            $name = $('#mini-contact-name'),
            $email = $('#mini-contact-email'),
            $msg = $('#mini-contact-message');

          if (nameVal.replace(/\s/g,'') == '') {
            $name.css({'border-color': '#ff0047'});
            setTimeout(function() {
              $name.attr('style', '');
            }, 200);
            return false;
          }

          if (emailVal.replace(/\s/g,'') == '' || !/^[-._a-z0-9]+@(?:[a-z0-9][-a-z0-9]+\.)+[a-z]{2,6}$/.test(emailVal)) {
            $email.css({'border-color': '#ff0047'});
            setTimeout(function() {
              $email.attr('style', '');
            }, 200);
            return false;
          }

          if (msgVal.replace(/\s/g,'') == '') {
            $msg.css({'border-color': '#ff0047'});
            setTimeout(function() {
              $msg.attr('style', '');
            }, 200);
            return false;
          }

          return true;
        }

        // ajax
        $("#mini-contact").submit(function() {
          var
            nameVal = $('#mini-contact-name').val(),
            emailVal = $('#mini-contact-email').val(),
            msgVal = $('#mini-contact-message').val(),
            adminEmailVal = $('#admin-email').val();

          if (adminEmailVal.replace(/\s/g,'') == '') adminEmailVal = 'none';
          if (!checkForm(nameVal, emailVal, msgVal)) return false;

          $('#contact-loader').empty().append(
            '<img src="<?php echo get_template_directory_uri(); ?>/img/loader.GIF" alt="">'
          );

          $.ajax({
            url: '<?php echo get_template_directory_uri(); ?>/assets/contact.php',
            type: 'POST',
            data: {
              name: nameVal,
              email: emailVal,
              msg: msgVal,
              adminEmail: adminEmailVal
            },
            dataType: 'json',
            success: function(data) {
              $('#contact-loader').empty();

              if (data.status == 'true') {
                $('#mini-contact-name').val('');
                $('#mini-contact-email').val('');
                $('#mini-contact-message').val('');
                $('#contact-result').empty().fadeIn().append(
                  '<?php esc_html_e('Your email has been sent successfully!', 'birdwp-theme'); ?>'
                );
                setTimeout(function() {
                  $('#contact-result').fadeOut();
                }, 4500);
              } else if (data.status == 'false') {
                $('#contact-result').empty().fadeIn().append(data.err);
                setTimeout(function() {
                  $('#contact-result').fadeOut();
                }, 4500);
              }
            },
            error: function() {
              $('#contact-loader').empty();
              $('#contact-result').empty().fadeIn().append(
                '<?php _e('Failed to send email', 'birdwp-theme'); ?>'
              );
              setTimeout(function() {
                $('#contact-result').fadeOut();
              }, 4500);
            }
          });

          return false;
        });
      });
    });
    </script>

    <?php
	  echo $after_widget;
  }

  function update($new_instance, $old_instance) {
    $instance = $old_instance;
    $instance['title'] = htmlspecialchars($new_instance['title'], ENT_QUOTES);
    $instance['email'] = htmlspecialchars($new_instance['email'], ENT_QUOTES);
    return $instance;
  }

  function form($instance) {
    $defaults = array(
      'title' 	=> __('Contact form' , 'birdwp-theme'),
      'email'		=> ''
    );
	
	  $instance = wp_parse_args((array) $instance, $defaults); ?>
    
    <p>
      <label for="<?php echo esc_attr($this->get_field_id('title')); ?>"><?php esc_html_e('Title:' , 'birdwp-theme'); ?></label>
      <input class="widefat" id="<?php echo esc_attr($this->get_field_id('title')); ?>" name="<?php echo esc_attr($this->get_field_name('title')); ?>" value="<?php echo esc_attr($instance['title']); ?>" />
    </p>
    <p>
      <label for="<?php echo esc_attr($this->get_field_id('email')); ?>"><?php esc_html_e('E-mail:' , 'birdwp-theme'); ?></label>
      <input class="widefat" id="<?php echo esc_attr($this->get_field_id('email')); ?>" name="<?php echo esc_attr($this->get_field_name('email')); ?>" value="<?php echo esc_attr($instance['email']); ?>" />
    </p>
  <?php
  }
}

// init widget
function BIRD_init_contact_form_widget() {
  register_widget('BIRD_Mini_Contact_Form');
}

add_action('widgets_init', 'BIRD_init_contact_form_widget');
