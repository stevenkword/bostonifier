<?php
/**
 * Bostonifier_REST_Widget class
 *
 * @package Bostonifier
 */

/**
 * Class Bostonifier REST Widget
 *
 * Translates English to Boston-speak.
 */
class Bostonifier_REST_Widget extends WP_Widget {

	/**
	 * Bostonifier rest endpoint
	 *
	 * @var rest_url
	 */
	public $rest_url;

	/**
	 * Bostonifier cache group
	 *
	 * @var cache_group
	 */
	public $cache_group = 'rest_widget';

	/**
	 * Bostonifier cache life (seconds)
	 *
	 * @var instance
	 */
	public $cache_life = 30;

	/**
	 * Register widget with WordPress.
	 */
	function __construct() {
		parent::__construct(
			'rest_widget', // Base ID.
			esc_html__( 'Bostonifier Widget', 'text_domain' ), // Name.
			array(
				'description' => esc_html__( 'A REST Widget', 'text_domain' ),
			) // Args.
		);
	}

	/**
	 * Front-end display of widget.
	 *
	 * @see WP_Widget::widget()
	 *
	 * @param array $args     Widget arguments.
	 * @param array $instance Saved values from database.
	 */
	public function widget( $args, $instance ) {
		$this->rest_url = $instance['title'];
		echo esc_html( $args['before_widget'] );
		if ( ! empty( $instance['title'] ) ) {
			echo esc_html( $args['before_title'] . apply_filters( 'widget_title', $instance['title'] ) . $args['after_title'] );
		}

		$route = '/wp-json/wp/v2/posts';
		$response = wp_cache_get( $route, $this->cache_group );

		if ( ! $response ) {
			$response = wp_safe_remote_get( $this->rest_url . $route );
			wp_cache_set( $this->rest_url . $route, $response, $this->cache_group, $this->cache_life );
		}

		if ( is_wp_error( $response ) ) {
			echo esc_html( '<ul>Could not reach host</ul>' );
			return;
		}

		$data = json_decode( $response['body'] );

		echo '<ul>';
		foreach ( $data as $post ) {
			echo esc_html( '<li><a href="' . esc_url( $post->link ) . '" target="_blank">' . $post->title->rendered . '</a>
			<br/>' . $post->excerpt->rendered . '</li>' );

		}
		echo '</ul>';

		echo esc_html( $args['after_widget'] );
	}

	/**
	 * Back-end widget form.
	 *
	 * @see WP_Widget::form()
	 *
	 * @param array $instance Previously saved values from database.
	 */
	public function form( $instance ) {
		$title = ! empty( $instance['title'] ) ? $instance['title'] : esc_html__( 'New title', 'text_domain' );
		?>
		<p>
		<label for="<?php echo esc_attr( $this->get_field_id( 'title' ) ); ?>"><?php esc_attr_e( 'Domain:', 'text_domain' ); ?></label>
		<input class="widefat" id="<?php echo esc_attr( $this->get_field_id( 'title' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'title' ) ); ?>" type="text" value="<?php echo esc_attr( $title ); ?>">
		</p>
		<?php
	}

	/**
	 * Sanitize widget form values as they are saved.
	 *
	 * @see WP_Widget::update()
	 *
	 * @param array $new_instance Values just sent to be saved.
	 * @param array $old_instance Previously saved values from database.
	 *
	 * @return array Updated safe values to be saved.
	 */
	public function update( $new_instance, $old_instance ) {
		$instance = array();
		$instance['title'] = ( ! empty( $new_instance['title'] ) ) ? strip_tags( $new_instance['title'] ) : '';

		return $instance;
	}
} // class rest_widget

/**
 * Register rest_widget widget
 */
function register_rest_widget() {
		register_widget( 'Bostonifier_REST_Widget' );
}
add_action( 'widgets_init', 'register_rest_widget' );
