<?php
	/*
	Plugin Name: DL IMGBanner widget
	Description: Удобный и простой способ добавить баннер на Ваш сайт
	Plugin URI: http://vcard.dd-l.name/wp-plugins/
	Tags: dl, widget, banner, images
	Version: 1.1
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
				'DL IMGBanner Widget' , // название виджета
				array( 'description' => 'Выводим баннер на сайт' ) // Опции
			);
		}


		public function widget ( $args , $instance ) {
 
	$is_title = $instance["stitle"];
 
	if ( $is_title ) {
		$title = apply_filters( 'widget_title' , $instance['title'] );
	}
 
	$image_id = isset( $instance['image_id'] ) ? $instance['image_id'] : '';
	$preview_image_src = $image_id ? $this->get_image_size( $image_id , 'full' ) : '';
	$url_img = isset( $instance['url_img'] ) && $instance['url_img'] ? $instance['url_img'] : '';
	$url_img_new_win = isset( $instance['url_img_new_win'] ) && $instance['url_img_new_win'] ? ' target="_blank" ' : '';
 
	extract( $args );
 
	echo $before_widget;
 
		if ( $is_title ) {
			echo $before_title . $title . $after_title;
		}
 
		$img_tag = "<img class=\"custom_media_image\" src=\"$preview_image_src\" style=\"width:100%; display:inline-block\" />";
 
		if ( $url_img ) {
			$img_tag = "<a href=\"$url_img\" $url_img_new_win >$img_tag<?</a>";
		}
 
		echo $img_tag;
 
	echo $after_widget;
}


		public function update ( $new_instance , $old_instance ) {
			$instance = array();
			$instance['image_id'] = strip_tags( $new_instance['image_id'] );
			$instance['title'] = strip_tags( $new_instance['title'] );
			$instance['stitle'] = strip_tags( $new_instance['stitle'] );
			$instance['url_img'] = strip_tags( $new_instance['url_img'] );
			$instance['url_img_new_win'] = strip_tags( $new_instance['url_img_new_win'] );
			return $instance;
		}


		public function form ( $instance ) {

			$image_id = isset( $instance['image_id'] ) ? $instance['image_id'] : '';
			$preview_image_src = $image_id ? $this->get_image_size( $image_id ) : '';
			//$preview_image_src = $image_id ? $this->get_image_size( $image_id , 'full' ) : '';
			$title = isset( $instance['title'] ) ? $instance['title'] : '';
			$stitle = isset( $instance['stitle'] ) ? $instance['stitle'] : '';
			$url_img = isset( $instance['url_img'] ) ? $instance['url_img'] : '';
			$url_img_new_win = isset( $instance['url_img_new_win'] ) ? $instance['url_img_new_win'] : '';
			?>
			<p>
				<div style="text-align: center;">
					<img
						class="dl-image-preview"
						src="<?php echo $preview_image_src; ?>"
					/>
				</div>
				<div style="text-align: center;">
				<input
					type="hidden"
					class="widefat dl-image-id"
					name="<?php echo $this->get_field_name( 'image_id' ); ?>"
					id="<?php echo $this->get_field_id( 'image_id' ); ?>"
					value="<?php echo $image_id; ?>"
				/>
				<a
					href="#"
					class="button custom_media_upload"
				    data-id="<?php echo $this->id; ?>"
				>
					Добавить медиафайл
				</a> 
				<a 
					href="javascript:void(0)"
					class="button dl-clean-image"
				>
					Очистить
				</a>
				</div>
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
					type="checkbox"
				/>
			</p>
			<p>
				<label for="<?php echo $this->get_field_id( 'url_img' ); ?>">URL баннера:</label>
				<input
					class="widefat"
					id="<?php echo $this->get_field_id( 'url_img' ); ?>"
					name="<?php echo $this->get_field_name( 'url_img' ); ?>"
					type="url"
					placeholder="http://"
					value="<?php echo esc_attr( $url_img ); ?>"
				/>
			</p>
			<p>
				<label for="<?php echo $this->get_field_id( 'url_img_new_win' ); ?>">Открывать в новом окне:</label> 
				<input
					id="<?php echo $this->get_field_id( 'url_img_new_win' ); ?>"
					name="<?php echo $this->get_field_name( 'url_img_new_win' ); ?>"
					value="true" <?php if ( $url_img_new_win ) echo 'checked="checked"'; ?>
					type="checkbox"
				/>
			</p>
		<?php
		}

		public function get_image_size ( $attachment_id , $size = 'thumbnail' ) {
			$thumb_info = wp_get_attachment_image_src( $attachment_id , $size );

			if ( is_array( $thumb_info ) ) {
				return $thumb_info['0'];
			}

			return false;
		}

	}


	add_action( 'admin_enqueue_scripts' , 'register_dl_imgbanner_script' );
	function register_dl_imgbanner_script () {
		wp_enqueue_media();
		wp_enqueue_script( 'dl_script' , plugins_url( '/media_upload.js' , __FILE__ ) );
	}