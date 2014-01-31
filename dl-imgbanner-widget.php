<?php
	/*
	Plugin Name: DL-IMGBanner widget
	Description: Виджет позволяет быстро и просто доавить баннер картинку на сайт
	Plugin URI: http://vcard.dd-l.name/wp-plugins/
	Tags: dl, widgwgt, banner, images
	Version: 0.1
	Author: Dyadya Lesha (info@dd-l.name)
	Author URI: http://dd-l.name
	*/


	// Загрузка виджета
	add_action( 'widgets_init' , 'register_dl_imgbanner_widget' );
	function register_dl_imgbanner_widget () {
		register_widget( 'DL_Imgbanner_Widget' );
	}


	class DL_Imgbanner_Widget extends WP_Widget {

		public function __construct () {
			parent::__construct(
				'DL_ImgBanner_Widget' , // идентификатор виджета
				'DL-IMGBanner Widget' , // название виджета
				array( 'description' => 'Выводим баннер на сайт' ) // Опции
			);
		}


		public function widget ( $args , $instance ) {
			if ( !$instance["stitle"] == '' ) $title = apply_filters( 'widget_title' , $instance['title'] );

			extract( $args );

			echo $before_widget;
			echo $before_title;
			echo $title;
			echo $after_title;
			echo '<img class="custom_media_image" src="' . $instance["image_uri"] . '" style="width:100%; display:inline-block" />';
			echo $after_widget;
		}


		public function update ( $new_instance , $old_instance ) {
			$instance = $old_instance;
			$instance['title'] = strip_tags( $new_instance['title'] );
			$instance['image_uri'] = strip_tags( $new_instance['image_uri'] );
			$instance['stitle'] = strip_tags( $new_instance['stitle'] );
			return $instance;
		}


		public function form ( $instance ) {
			$image_uri = isset( $instance['image_uri'] ) ? $instance['image_uri'] : '';
			$title = isset( $instance['title'] ) ? $instance['title'] : '';
			$stitle = isset( $instance['stitle'] ) ? $instance['stitle'] : '';
			?>
			<p>
				<img class="custom_media_image" src="<?php echo $image_uri; ?>"
				     style="width:100%; display:inline-block" />
				<input
					type="hidden"
					class="widefat custom_media_url"
					name="<?php echo $this->get_field_name( 'image_uri' ); ?>"
					id="<?php echo $this->get_field_id( 'image_uri' ); ?>"
					value="<?php echo $image_uri; ?>"
				/>
				<a href="#" style="width: 100%; text-align: center;" class="button custom_media_upload">Загрузить/Выбрать
					из библиотеки</a>
			</p>
			<p>
				<label for="<?php echo $this->get_field_id( 'title' ); ?>">Заголовок:</label>
				<input
					class="widefat"
					id="<?php echo $this->get_field_id( 'title' ); ?>"
					name="<?php echo $this->get_field_name( 'title' ); ?>"
					type="text"
					value="<?php echo esc_attr( $title ); ?>"
				/>
			</p>
			<p>
				<label for="<?php echo $this->get_field_id( 'stitle' ); ?>">Выводить заголовок:</label>
				<input
					id="<?php echo $this->get_field_id( 'stitle' ); ?>"
					name="<?php echo $this->get_field_name( 'stitle' ); ?>"
					value="true" <?php if ( $stitle ) echo 'checked="checked"'; ?>
					type="checkbox" checked
				/>
			</p>
		<?php
		}
	}


	add_action( 'admin_enqueue_scripts' , 'register_dl_imgbanner_script' );
	function register_dl_imgbanner_script () {
		wp_enqueue_media();
		wp_enqueue_script( 'dl_script' , plugins_url( '/media_upload.js' , __FILE__ ) );
	}


