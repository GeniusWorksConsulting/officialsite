<?php
class WpProQuiz_Controller_StyleManager extends WpProQuiz_Controller_Controller {
	
	public function route() {
		$this->show();
	}
	
	private function show() {
		global $learndash_assets_loaded;
		
		wp_enqueue_style(
			'wpProQuiz_front_style', 
			plugins_url('css/wpProQuiz_front'. ( ( defined( 'WPPROQUIZ_DEV' ) && ( WPPROQUIZ_DEV === true ) ) ? '' : '.min') .'.css', WPPROQUIZ_FILE),
			array(),
			WPPROQUIZ_VERSION
		);
		$learndash_assets_loaded['styles']['wpProQuiz_front_style'] = __FUNCTION__;
		
		$view = new WpProQuiz_View_StyleManager();
		
		$view->show();
	}
}